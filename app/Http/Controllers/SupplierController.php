<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Operation;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    // список поставщиков с фильтрацией, поиском
    public function index(Request $request)
    {
        $query = Supplier::query();
        $user = $request->user();

        // фильтрация по статусу активности (если передано)
        if ($request->filled('is_active')) {
            $query->where('is_active', (bool) $request->is_active);
        }

        // поиск по названию, контакту, телефону, email
        if ($request->has('search') && $request->search) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")
                    ->orWhere('contact_person', 'like', "%$s%")
                    ->orWhere('phone', 'like', "%$s%")
                    ->orWhere('email', 'like', "%$s%");
            });
        }

        // сортировка: поле и направление
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        $query->orderBy($sort, $order);

        return $query->get();
    }

    // создание поставщика (только админ)
    public function store(StoreSupplierRequest $request)
    {
        // проверка прав: только админ
        if (Auth::user()->role != 'admin') {
            return response()->json(["error" => "Недостаточно прав"], 403);
        }
        // создание поставщика с валидированными данными
        Supplier::create($request->all());
        return response()->json(["message" => "ok"]);
    }

    // обновление поставщика (только админ)
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        // проверка прав: только админ
        if (Auth::user()->role != 'admin') {
            return response()->json(["error" => "Недостаточно прав"], 403);
        }
        // обновление поставщика с валидированными данными
        $supplier->update($request->all());
        return response()->json(["message" => "ok"]);
    }

    // удаление поставщика (только админ)
    public function destroy(Request $request, $id)
    {
        // проверка прав: только админ
        if ($request->user()->role != 'admin') {
            return response()->json(['errors' => ['permission' => ['Недостаточно прав']]], 403);
        }

        // защита: нельзя удалить поставщика, если есть связанные операции
        if (Supplier::findOrFail($id)->operations()->count() > 0) {
            return response()->json([
                'errors' => ['supplier' => ['Нельзя удалить: есть связанные операции']]
            ], 400);
        }

        // удаление поставщика
        Supplier::destroy($id);
        return response()->json(['message' => 'ok']);
    }

    // отчёт по поставщикам: статистика по операциям (только админ)
    public function report(Request $request)
    {
        // проверка прав: только админ
        if ($request->user()->role != 'admin') {
            return response()->json(['errors' => ['permission' => ['Недостаточно прав']]], 403);
        }

        // определение периода отчёта
        $period = $request->input('period', 'all');
        $startDate = null;

        if ($period == 'month') {
            $startDate = now()->startOfMonth();
        } elseif ($period == 'year') {
            $startDate = now()->startOfYear();
        }

        // загрузка поставщиков с подсчётом операций и сумм
        $suppliers = Supplier::withCount([
            'operations' => fn($q) => $startDate ? $q->where('created_at', '>=', $startDate) : null
        ])->withSum([
                    'operations' => fn($q) => $startDate ? $q->where('created_at', '>=', $startDate) : null
                ], 'price')->get();

        $reports = [];
        foreach ($suppliers as $s) {
            // получение операций для детального расчёта
            $ops = $startDate
                ? $s->operations()->where('created_at', '>=', $startDate)->get()
                : $s->operations;

            // расчёт метрик: приход, расход, баланс
            $reports[] = [
                'supplier' => $s,
                'operations_count' => $ops->count(),
                'total_income' => $ops->where('type', 'Приход')->sum('price'),
                'total_expense' => $ops->where('type', 'Расход')->sum('price'),
            ];
        }

        return $reports;
    }

    // генерация PDF-отчёта по поставщику (только админ)
    public function pdfSupplierReport($id)
    {
        // проверка прав: только админ
        if (Auth::user()->role != 'admin') {
            return response()->json([
                "errors" => ["permission" => ["Недостаточно прав"]]
            ], 403);
        }

        $supplier = Supplier::findOrFail($id);

        // загрузка операций поставщика с связями
        $operations = Operation::where('supplier_id', $id)
            ->with(['user', 'manager', 'items.product'])
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
            body { 
                font-family: dejavu sans, sans-serif; 
                font-size: 12px;
            }
            .header { text-align: center; margin-bottom: 20px; }
            .title { color: #667eea; font-size: 22px; font-weight: bold; }
            .supplier-name { text-align: center; color: #333; margin-bottom: 20px; font-size: 16px; }
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
            <h1 class="title">Отчёт по поставщику</h1>
            <p class="supplier-name">Поставщик: ' . e($supplier->name) . '</p>
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
            <thead><tr><th>Дата</th><th>Кладовщик</th><th>Менеджер</th><th>Товары</th><th>Сумма</th><th>Статус</th></tr></thead>
            <tbody>';

        foreach ($incomeOps as $op) {
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

        // генерация и скачивание PDF
        $pdf = App::make('dompdf.wrapper');

        // настройки для поддержки кириллицы
        $pdf->set_option('defaultFont', 'dejavu sans');
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->set_option('isRemoteEnabled', true);

        $pdf->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));

        return $pdf->download("otchet_postavschik_{$id}.pdf");
    }
}