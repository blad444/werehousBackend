<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    // список категорий (доступно всем авторизованным ролям)
    public function categoriesAPI()
    {
        // проверка прав: админ, менеджер или кладовщик
        if (!in_array(Auth::user()->role, ['admin', 'manager', 'kladovsik'])) {
            return response()->json(["error" => "Недостаточно прав"], 403);
        }
        // возврат всех категорий
        return Category::all();
    }

    // создание категории (только админ)
    public function addCategoryAPI(StoreCategoryRequest $request)
    {
        // проверка прав: только админ
        if (Auth::user()->role != 'admin') {
            return response()->json(["error" => "Недостаточно прав"], 403);
        }
        // создание категории
        Category::create($request->all());
        return response()->json(["message" => "ok"]);
    }

    // обновление категории (только админ)
    public function updateCategoryAPI(UpdateCategoryRequest $request, Category $category)
    {
        // проверка прав: только админ
        if (Auth::user()->role != 'admin') {
            return response()->json(["error" => "Недостаточно прав"], 403);
        }
        // обновление категории
        $category->update($request->all());
        return response()->json(["message" => "ok"]);
    }

    // удаление категории (только админ)
    public function deleteCategoryAPI(Category $category)
    {
        // проверка прав: только админ
        if (Auth::user()->role != 'admin') {
            return response()->json(["error" => "Недостаточно прав"], 403);
        }
        // удаление категории
        $category->delete();
        return response()->json(["message" => "ok"]);
    }
}