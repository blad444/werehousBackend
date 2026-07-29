<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\ChangeUserRoleRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // регистрация нового пользователя
    public function regAPI(RegisterRequest $request)
    {
        // создание пользователя с ролью кладовщика по умолчанию
        $user = User::create([
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'kladovsik',
        ]);

        // генерация токена доступа
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            "message" => "Успешная регистрация",
            "token" => $token
        ]);
    }

    // авторизация пользователя по email и паролю
    public function authAPI(AuthRequest $request)
    {
        // поиск пользователя по email
        $user = User::where('email', $request->email)->first();

        // проверка пароля и выдача токена
        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                "message" => "Успешная авторизация",
                "token" => $token,
            ]);
        }

        // ошибка авторизации
        return response()->json([
            "errors" => [
                "email" => ["Ошибка авторизации"]
            ]
        ], 401);
    }

    // выход из системы: удаление текущего токена
    public function logoutAPI(Request $request)
    {
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
            return response()->json(["message" => "Успешный выход"]);
        }
        return response()->json(["message" => "Пользователь не авторизован"], 401);
    }

    // получение данных текущего авторизованного пользователя
    public function userAPI(Request $request)
    {
        return $request->user();
    }

    // обновление профиля: ФИО, телефон, email
    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = $request->user();

        // обновление данных профиля
        $user->update([
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        return response()->json(["message" => "ok"]);
    }

    // смена пароля с проверкой текущего
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = $request->user();

        // проверка текущего пароля
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                "errors" => [
                    "current_password" => ["Текущий пароль неверный"]
                ]
            ], 422);
        }

        // удаление всех старых токенов
        $user->tokens()->delete();

        // установка нового пароля
        $user->password = Hash::make($request->new_password);
        $user->save();

        // генерация нового токена
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            "message" => "Пароль успешно изменён",
            "token" => $token
        ]);
    }

    // список всех пользователей (только админ)
    public function usersAPI(Request $request)
    {
        // проверка прав: только админ
        if ($request->user()->role != 'admin') {
            return response()->json([
                "errors" => [
                    "permission" => ["Недостаточно прав"]
                ]
            ], 403);
        }

        return User::all();
    }

    // список менеджеров (только админ)
    public function managersAPI(Request $request)
    {
        // проверка прав: только админ
        if ($request->user()->role != 'admin') {
            return response()->json([
                "errors" => [
                    "permission" => ["Недостаточно прав"]
                ]
            ], 403);
        }

        return User::where('role', 'manager')->get();
    }

    // изменение роли пользователя (только админ)
    public function changeUserRole(ChangeUserRoleRequest $request, $userId)
    {
        // нельзя изменить свою собственную роль
        if ($userId == $request->user()->id) {
            return response()->json([
                "error" => "Нельзя изменить свою роль"
            ], 403);
        }

        $user = User::findOrFail($userId);

        // защита: нельзя удалить последнего администратора
        $adminCount = User::where('role', 'admin')->count();
        if ($user->role == 'admin' && $adminCount == 1) {
            return response()->json([
                "error" => "Нельзя удалить последнего администратора"
            ], 400);
        }

        // обновление роли
        $user->role = $request->role;
        $user->save();

        return response()->json([
            "message" => "Роль пользователя успешно изменена",
            "user" => $user
        ]);
    }

    // список кладовщиков (админ, менеджер)
    public function kladovsiksAPI(Request $request)
    {
        // проверка прав: админ или менеджер
        if (!in_array($request->user()->role, ['admin', 'manager'])) {
            return response()->json([
                "errors" => ["permission" => ["Недостаточно прав"]]
            ], 403);
        }

        return User::where('role', 'kladovsik')->get();
    }
}