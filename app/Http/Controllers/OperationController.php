<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOperationRequest;
use App\Http\Requests\UpdateOperationRequest;
use App\Models\Operation;
use App\Models\Operation_item;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OperationController extends Controller
{
    // список моих операций (только кладовщик)
    public function myOperationsAPI()
    {
        // проверка прав: только кладовщик
        if (Auth::user()->role != 'kladovsik') {
            return response()->json([
                "errors" => ["permission" => ["Недостаточно прав"]]
            ], 403);
        }

        // возврат операций текущего пользователя с связями
        return Operation::where('user_id', Auth::id())
            ->with('items.product', 'manager', 'supplier')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // список всех операций (менеджер, админ)
    public function operationsAPI()
    {
        // проверка прав: менеджер или админ
        if (!in_array(Auth::user()->role, ['manager', 'admin'])) {
            return response()->json([
                "errors" => ["permission" => ["Недостаточно прав"]]
            ], 403);
        }

        $statuses = ['Новый', 'В процессе', 'Выполнен', 'Отменен'];

        // менеджер видит: новые без менеджера + свои принятые
        if (Auth::user()->role == 'manager') {
            $operations = Operation::where(function ($query) {
                $query->where('manager_id', Auth::id())
                    ->orWhereNull('manager_id');
            })
                ->with('items.product', 'user', 'manager', 'supplier')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // админ видит все операции
            $operations = Operation::with('items.product', 'user', 'manager', 'supplier')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return [
            "operations" => $operations,
            "statuses" => $statuses
        ];
    }

    // создание операции (только кладовщик)
    public function addOperationAPI(StoreOperationRequest $request)
    {
        // проверка прав: только кладовщик
        if (Auth::user()->role != 'kladovsik') {
            return response()->json([
                "errors" => ["permission" => ["Только кладовщик может создавать операции"]]
            ], 403);
        }

        // проверка: для прихода обязателен поставщик
        if ($request->type == 'Приход' && !$request->supplier_id) {
            return response()->json([
                "errors" => ["supplier_id" => ["Для операции прихода необходимо указать поставщика"]]
            ], 400);
        }

        DB::beginTransaction();
        try {
            $total = 0;
            $items = [];

            // проверка остатков товаров перед созданием
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) {
                    return response()->json([
                        "errors" => ["product" => ["Товар не найден"]]
                    ], 400);
                }
                if ($request->type == 'Расход' && $product->quantity < $item['quantity']) {
                    return response()->json([
                        "errors" => ["quantity" => ["Недостаточно товара '{$product->title}'. Доступно: {$product->quantity}"]]
                    ], 400);
                }
            }

            // обновление остатков и сбор данных позиций
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                if ($request->type == 'Приход') {
                    $product->quantity += $item['quantity'];
                } else {
                    $product->quantity -= $item['quantity'];
                }
                $product->availability = $product->quantity > 0 ? 'В наличии' : 'Нет в наличии';
                $product->save();

                $price = $product->price * $item['quantity'];
                $total += $price;
                $items[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ];
            }

            // создание записи операции
            $operation = Operation::create([
                'user_id' => Auth::id(),
                'manager_id' => null,
                'supplier_id' => $request->type == 'Приход' ? $request->supplier_id : null,
                'price' => $total,
                'type' => $request->type,
                'status' => 'Новый',
            ]);

            // создание позиций операции
            foreach ($items as $item) {
                Operation_item::create([
                    'operation_id' => $operation->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            DB::commit();
            return response()->json(["message" => "ok"]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "errors" => ["server" => ["Ошибка сервера: " . $e->getMessage()]]
            ], 500);
        }
    }

    // изменение статуса операции (менеджер, админ)
    public function statusAPI(UpdateOperationRequest $request, $id)
    {
        // проверка прав: только менеджер или админ
        if (!in_array(Auth::user()->role, ['manager', 'admin'])) {
            return response()->json([
                "errors" => ["permission" => ["Недостаточно прав"]]
            ], 403);
        }

        $operation = Operation::with(['items.product'])->findOrFail($id);
        $oldStatus = $operation->status;
        $newStatus = $request->status;

        // менеджер может менять только свои операции или новые
        if (
            Auth::user()->role == 'manager'
            && $operation->manager_id != Auth::id()
            && $oldStatus != 'Новый'
        ) {
            return response()->json([
                "errors" => ["permission" => ["Вы не можете изменять эту операцию"]]
            ], 403);
        }

        // если статус не меняется - выходим
        if ($oldStatus == $newStatus) {
            return response()->json(["message" => "ok"]);
        }

        DB::beginTransaction();
        try {
            // корректировка остатков при изменении статуса
            if ($newStatus == 'Отменен' && $oldStatus != 'Отменен') {
                // отмена операции: возвращаем товар на склад или убираем приход
                foreach ($operation->items as $item) {
                    $product = $item->product;
                    if (!$product)
                        continue;

                    if ($operation->type == 'Расход') {
                        // возвращаем товар на склад
                        $product->quantity += $item->quantity;
                    } elseif ($operation->type == 'Приход') {
                        // убираем товар со склада (отменяем приход)
                        $product->quantity -= $item->quantity;
                        if ($product->quantity < 0) {
                            throw new \Exception("Недостаточно товара '{$product->title}' для отмены прихода");
                        }
                    }
                    // обновляем статус наличия
                    $product->availability = $product->quantity > 0 ? 'В наличии' : 'Нет в наличии';
                    $product->save();
                }
            } elseif ($oldStatus == 'Отменен' && $newStatus != 'Отменен') {
                // восстановление операции: снова списываем или добавляем товар
                foreach ($operation->items as $item) {
                    $product = $item->product;
                    if (!$product)
                        continue;

                    if ($operation->type == 'Расход') {
                        // проверяем остаток и списываем товар
                        if ($product->quantity < $item->quantity) {
                            throw new \Exception("Недостаточно товара '{$product->title}' на складе");
                        }
                        $product->quantity -= $item->quantity;
                    } elseif ($operation->type == 'Приход') {
                        // добавляем товар на склад
                        $product->quantity += $item->quantity;
                    }
                    // обновляем статус наличия
                    $product->availability = $product->quantity > 0 ? 'В наличии' : 'Нет в наличии';
                    $product->save();
                }
            }

            // обновление статуса и назначение текущего пользователя как менеджера
            $operation->update([
                'status' => $newStatus,
                'manager_id' => Auth::id(),
            ]);

            DB::commit();
            return response()->json(["message" => "ok"]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Ошибка изменения статуса операции #{$id}: " . $e->getMessage());
            return response()->json([
                "errors" => ["server" => ["Ошибка: " . $e->getMessage()]]
            ], 500);
        }
    }

    // назначение или снятие менеджера с операции (админ, менеджер)
    public function assignManagerAPI(Request $request, $id)
    {
        $operation = Operation::findOrFail($id);

        // админ может назначать или снимать любого менеджера
        if (Auth::user()->role == 'admin') {
            $managerId = $request->input('manager_id');
            // если передано пустое значение - снимаем назначение, иначе - назначаем
            $operation->manager_id = $managerId ? $managerId : null;
            $operation->save();

            return response()->json([
                "message" => $managerId ? "Менеджер успешно назначен" : "Менеджер снят с операции"
            ]);
        }

        // менеджер может взять только операцию без назначенного менеджера
        if (
            Auth::user()->role == 'manager'
            && !$operation->manager_id
        ) {
            $operation->manager_id = Auth::id();
            $operation->save();
            return response()->json(["message" => "Операция успешно взята"]);
        }

        // если ни одно условие не выполнено - отказ в доступе
        return response()->json([
            "errors" => ["permission" => ["Недостаточно прав"]]
        ], 403);
    }

    // отчёт по менеджерам: статистика операций (только админ)
    public function reportsAPI(Request $request)
    {
        // проверка прав: только админ
        if ($request->user()->role != 'admin') {
            return response()->json([
                "errors" => ["permission" => ["Недостаточно прав"]]
            ], 403);
        }

        $managers = User::where('role', 'manager')->get();
        $reports = [];

        // формирование отчёта для каждого менеджера
        foreach ($managers as $manager) {
            $operations = Operation::where('manager_id', $manager->id)->get();
            $reports[] = [
                'manager' => $manager,
                'orders_count' => $operations->count(),
                'total_amount' => $operations->sum('price')
            ];
        }

        return $reports;
    }

    // отчёт по кладовщикам: статистика по периоду (только админ)
    public function kladovsiksReportAPI(Request $request)
    {
        // проверка прав: только админ
        if ($request->user()->role != 'admin') {
            return response()->json([
                "errors" => ["permission" => ["Недостаточно прав"]]
            ], 403);
        }

        // определение периода отчёта
        $period = $request->input('period', 'all');
        $startDate = null;

        if ($period == 'month') {
            $startDate = now()->startOfMonth();
        } elseif ($period == 'year') {
            $startDate = now()->startOfYear();
        }

        // получение всех кладовщиков
        $kladovsiks = User::where('role', 'kladovsik')->get();
        $reports = [];

        // формирование отчёта для каждого кладовщика
        foreach ($kladovsiks as $k) {
            $query = Operation::where('user_id', $k->id);
            // фильтрация по дате, если указан период
            if ($startDate) {
                $query->where('created_at', '>=', $startDate);
            }
            $operations = $query->get();

            // расчёт метрик: приход, расход
            $income = $operations->where('type', 'Приход')->sum('price');
            $expense = $operations->where('type', 'Расход')->sum('price');

            $reports[] = [
                'kladovsik' => $k,
                'operations_count' => $operations->count(),
                'total_income' => $income,
                'total_expense' => $expense,
            ];
        }

        return $reports;
    }

    // отчёт по операциям за месяц: детализация (только админ)
    public function operationsPeriodReportAPI(Request $request)
    {
        // проверка прав: только админ
        if ($request->user()->role != 'admin') {
            return response()->json([
                "errors" => ["permission" => ["Недостаточно прав"]]
            ], 403);
        }

        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $startDate = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // загрузка операций за период с связями
        $operations = Operation::whereBetween('created_at', [$startDate, $endDate])
            ->with(['user', 'manager', 'supplier', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->get();

        $incomeOps = $operations->where('type', 'Приход');
        $expenseOps = $operations->where('type', 'Расход');

        // ручное название месяца для надёжности
        $months = [
            1 => 'Январь',
            2 => 'Февраль',
            3 => 'Март',
            4 => 'Апрель',
            5 => 'Май',
            6 => 'Июнь',
            7 => 'Июль',
            8 => 'Август',
            9 => 'Сентябрь',
            10 => 'Октябрь',
            11 => 'Ноябрь',
            12 => 'Декабрь'
        ];
        $monthName = $months[$month] ?? 'Месяц';

        return [
            'period' => [
                'start' => $startDate->format('d.m.Y'),
                'end' => $endDate->format('d.m.Y'),
                'label' => "$monthName $year"
            ],
            'summary' => [
                'total_operations' => $operations->count(),
                'income_count' => $incomeOps->count(),
                'income_total' => $incomeOps->sum('price'),
                'expense_count' => $expenseOps->count(),
                'expense_total' => $expenseOps->sum('price'),
            ],
            'income' => $incomeOps->values(),
            'expense' => $expenseOps->values(),
        ];
    }

    // генерация PDF-отчёта по менеджеру (только админ)
    public function pdfReport($managerId)
    {
        // проверка прав: только админ
        if (Auth::user()->role != 'admin') {
            return response()->json([
                "errors" => ["permission" => ["Недостаточно прав"]]
            ], 403);
        }

        $manager = User::where('role', 'manager')->find($managerId);
        if (!$manager) {
            return response()->json([
                "errors" => ["manager" => ["Менеджер не найден"]]
            ], 404);
        }

        $operations = Operation::where('manager_id', $manager->id)->get();
        $totalAmount = $operations->sum('price');
        $ordersCount = $operations->count();

        // формирование HTML для PDF
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <style>
                body { font-family: DejaVu Sans, sans-serif; font-size: 14px; }
                .header { text-align: center; margin-bottom: 20px; }
                .title { color: #667eea; font-size: 24px; font-weight: bold; }
                .info { margin: 15px 0; line-height: 1.6; }
                .stats { display: flex; gap: 30px; margin: 20px 0; }
                .stat-box { background: #f5f7fa; padding: 15px; border-radius: 8px; flex: 1; text-align: center; }
                .stat-value { font-size: 28px; font-weight: bold; color: #667eea; }
                .stat-label { color: #666; font-size: 12px; margin-top: 5px; }
                .footer { margin-top: 50px; text-align: center; color: #999; font-size: 11px; border-top: 1px solid #eee; padding-top: 15px; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1 class="title">Отчёт менеджера</h1>
                <p>Складской учёт</p>
            </div>
            <div class="info">
                <p><strong>ФИО:</strong> ' . e($manager->full_name) . '</p>
                <p><strong>Телефон:</strong> ' . e($manager->phone) . '</p>
                <p><strong>Email:</strong> ' . e($manager->email) . '</p>
            </div>
            <div class="stats">
                <div class="stat-box">
                    <div class="stat-value">' . $ordersCount . '</div>
                    <div class="stat-label">Обработано операций</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value">' . number_format($totalAmount, 0, '', ' ') . ' ₽</div>
                    <div class="stat-label">На сумму</div>
                </div>
            </div>  
        </body>
        </html>';

        // генерация и скачивание PDF
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($html);
        return $pdf->download("otchet_manager_{$manager->full_name}.pdf");
    }

    // генерация PDF-отчёта по кладовщику (только админ)
    public function pdfKladovsikReport($kladovsikId)
    {
        // проверка прав: только админ
        if (Auth::user()->role != 'admin') {
            return response()->json([
                "errors" => ["permission" => ["Недостаточно прав"]]
            ], 403);
        }

        $kladovsik = User::where('role', 'kladovsik')->find($kladovsikId);
        if (!$kladovsik) {
            return response()->json([
                "errors" => ["kladovsik" => ["Кладовщик не найден"]]
            ], 404);
        }

        $operations = Operation::where('user_id', $kladovsikId)->get();
        $income = $operations->where('type', 'Приход');
        $expense = $operations->where('type', 'Расход');
        $totalIncome = $income->sum('price');
        $totalExpense = $expense->sum('price');

        // формирование HTML для PDF
        $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
            body { font-family: DejaVu Sans, sans-serif; font-size: 14px; }
            .header { text-align: center; margin-bottom: 20px; }
            .title { color: #667eea; font-size: 24px; font-weight: bold; }
            .info { margin: 15px 0; line-height: 1.6; }
            .stats { display: flex; gap: 20px; margin: 20px 0; }
            .stat-box { background: #f5f7fa; padding: 15px; border-radius: 8px; flex: 1; text-align: center; }
            .stat-value { font-size: 24px; font-weight: bold; color: #667eea; }
            .stat-label { color: #666; font-size: 12px; margin-top: 5px; }
            .positive { color: #2e7d32; }
            .negative { color: #c62828; }
            .footer { margin-top: 50px; text-align: center; color: #999; font-size: 11px; border-top: 1px solid #eee; padding-top: 15px; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
            th { background: #f5f7fa; }
        </style>
    </head>
    <body>
        <div class="header">
            <h1 class="title">Отчёт кладовщика</h1>
            <p>Складской учёт</p>
        </div>
        <div class="info">
            <p><strong>ФИО:</strong> ' . e($kladovsik->full_name) . '</p>
            <p><strong>Телефон:</strong> ' . e($kladovsik->phone) . '</p>
            <p><strong>Email:</strong> ' . e($kladovsik->email) . '</p>
        </div>
        <div class="stats">
            <div class="stat-box">
                <div class="stat-value">' . $operations->count() . '</div>
                <div class="stat-label">Всего операций</div>
            </div>
            <div class="stat-box">
                <div class="stat-value positive">' . number_format($totalIncome, 0, '', ' ') . ' ₽</div>
                <div class="stat-label">Приход</div>
            </div>
            <div class="stat-box">
                <div class="stat-value negative">' . number_format($totalExpense, 0, '', ' ') . ' ₽</div>
                <div class="stat-label">Расход</div>
            </div>
        </div>
    </body>
    </html>';

        // генерация и скачивание PDF
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($html);
        return $pdf->download("otchet_kladovsik_{$kladovsik->full_name}.pdf");
    }

    // генерация PDF-отчёта по операциям за период (только админ)
    public function pdfOperationsReport($month, $year)
    {
        // проверка прав: только админ
        if (Auth::user()->role != 'admin') {
            return response()->json([
                "errors" => ["permission" => ["Недостаточно прав"]]
            ], 403);
        }

        \Carbon\Carbon::setLocale('ru');
        $startDate = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // ручное название месяца для надёжности
        $months = [
            1 => 'Январь',
            2 => 'Февраль',
            3 => 'Март',
            4 => 'Апрель',
            5 => 'Май',
            6 => 'Июнь',
            7 => 'Июль',
            8 => 'Август',
            9 => 'Сентябрь',
            10 => 'Октябрь',
            11 => 'Ноябрь',
            12 => 'Декабрь'
        ];
        $monthName = $months[$month] ?? 'Месяц';
        $periodLabel = "$monthName $year";

        // загрузка операций за период с связями
        $operations = Operation::whereBetween('created_at', [$startDate, $endDate])
            ->with(['user', 'manager', 'supplier', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->get();

        $incomeOps = $operations->where('type', 'Приход');
        $expenseOps = $operations->where('type', 'Расход');

        // формирование HTML для PDF
        $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta http-equiv="Content-Language" content="ru"/>
        <style>
            body { font-family: dejavu sans, sans-serif; font-size: 12px; }
            .header { text-align: center; margin-bottom: 20px; }
            .title { color: #667eea; font-size: 22px; font-weight: bold; }
            .period { text-align: center; color: #666; margin-bottom: 20px; }
            .summary { display: flex; gap: 15px; margin: 20px 0; }
            .summary-box { background: #f5f7fa; padding: 12px; border-radius: 6px; flex: 1; text-align: center; }
            .summary-value { font-size: 20px; font-weight: bold; color: #667eea; }
            .summary-label { color: #666; font-size: 11px; margin-top: 3px; }
            .income { color: #2e7d32; }
            .expense { color: #c62828; }
            table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 11px; }
            th, td { padding: 6px 8px; text-align: left; border-bottom: 1px solid #ddd; }
            th { background: #f5f7fa; font-weight: 600; }
            .footer { margin-top: 30px; text-align: center; color: #999; font-size: 10px; border-top: 1px solid #eee; padding-top: 10px; }
        </style>
    </head>
    <body>
        <div class="header">
            <h1 class="title">Отчёт по операциям</h1>
            <p class="period">' . $periodLabel . '</p>
        </div>
        <div class="summary">
            <div class="summary-box">
                <div class="summary-value">' . $operations->count() . '</div>
                <div class="summary-label">Всего операций</div>
            </div>
            <div class="summary-box">
                <div class="summary-value income">' . number_format($incomeOps->sum("price"), 0, "", " ") . ' ₽</div>
                <div class="summary-label">Приход (' . $incomeOps->count() . ')</div>
            </div>
            <div class="summary-box">
                <div class="summary-value expense">' . number_format($expenseOps->sum("price"), 0, "", " ") . ' ₽</div>
                <div class="summary-label">Расход (' . $expenseOps->count() . ')</div>
            </div>
        </div>
        <h3 style="margin-top: 25px; color: #2e7d32;">Приход</h3>
        <table>
            <thead><tr><th>Дата</th><th>Кладовщик</th><th>Менеджер</th><th>Поставщик</th><th>Товары</th><th>Сумма</th><th>Статус</th></tr></thead>
            <tbody>';

        foreach ($incomeOps as $op) {
            $html .= '<tr>
            <td>' . $op->created_at->format("d.m.Y H:i") . '</td>
            <td>' . e($op->user?->full_name ?? "-") . '</td>
            <td>' . e($op->manager?->full_name ?? "-") . '</td>
            <td>' . e($op->supplier?->name ?? "-") . '</td>
            <td>' . $op->items->count() . '</td>
            <td>' . number_format($op->price, 0, "", " ") . ' ₽</td>
            <td>' . e($op->status) . '</td>
        </tr>';
        }

        $html .= '</tbody></table>
        <h3 style="margin-top: 25px; color: #c62828;">Расход</h3>
        <table>
            <thead><tr><th>Дата</th><th>Кладовщик</th><th>Менеджер</th><th>Товары</th><th>Сумма</th><th>Статус</th></tr></thead>
            <tbody>';

        foreach ($expenseOps as $op) {
            $html .= '<tr>
            <td>' . $op->created_at->format("d.m.Y H:i") . '</td>
            <td>' . e($op->user?->full_name ?? "-") . '</td>
            <td>' . e($op->manager?->full_name ?? "-") . '</td>
            <td>' . $op->items->count() . '</td>
            <td>' . number_format($op->price, 0, "", " ") . ' ₽</td>
            <td>' . e($op->status) . '</td>
        </tr>';
        }

        $html .= '</tbody></table>
        <div class="footer">
            <p>Сформировано: ' . date("d.m.Y H:i") . ' | Система складского учёта</p>
        </div>
    </body>
    </html>';

        // настройки PDF для поддержки кириллицы
        $pdf = App::make('dompdf.wrapper');
        $pdf->set_option('defaultFont', 'dejavu sans');
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->set_option('isRemoteEnabled', true);
        $pdf->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));

        return $pdf->download("otchet_operacii_{$year}_{$month}.pdf");
    }
}