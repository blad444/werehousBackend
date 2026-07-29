<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // список товаров (доступно всем авторизованным ролям)
    public function productsAPI()
    {
        // проверка прав: админ, менеджер или кладовщик
        if (!in_array(Auth::user()->role, ['admin', 'manager', 'kladovsik'])) {
            return response()->json(["error" => "Недостаточно прав"], 403);
        }
        // возврат всех товаров
        return Product::all();
    }

    // создание товара (только админ)
    public function addProductAPI(StoreProductRequest $request)
    {
        // проверка прав: только админ
        if (Auth::user()->role != 'admin') {
            return response()->json(["error" => "Недостаточно прав"], 403);
        }

        $file = null;

        // загрузка фотографии товара, если есть
        if ($request->hasFile('photo')) {
            $file = "public/" . Storage::disk("publicphoto")->put("products", $request->photo);
        }

        // создание товара с данными и фотографией
        Product::create(['photo' => $file] + $request->all());

        return response()->json(["message" => "ok"]);
    }

    // обновление товара (только админ)
    public function updateProductAPI(UpdateProductRequest $request, Product $product)
    {
        // проверка прав: только админ
        if (Auth::user()->role != 'admin') {
            return response()->json(["error" => "Недостаточно прав"], 403);
        }

        $photo = [];

        // загрузка новой фотографии, если есть
        if ($request->hasFile('photo')) {
            $path = "public/" . Storage::disk("publicphoto")->put("products", $request->photo);
            $photo = ["photo" => $path];
        }

        $data = $request->all();

        // обновление товара с новыми данными и фотографией
        $product->update($photo + $data);

        return response()->json(["message" => "ok"]);
    }

    // удаление товара (только админ)
    public function deleteProductAPI(Product $product)
    {
        // проверка прав: только админ
        if (Auth::user()->role != 'admin') {
            return response()->json(["error" => "Недостаточно прав"], 403);
        }
        // удаление товара
        $product->delete();
        return response()->json(["message" => "ok"]);
    }
}