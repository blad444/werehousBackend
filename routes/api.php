<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// публичные маршруты (без авторизации)
Route::post('/register', [UserController::class, 'regAPI']);
Route::post('/auth', [UserController::class, 'authAPI']);

// защищённые маршруты (требуется авторизация)
Route::middleware('auth:sanctum')->group(function () {

    // профиль пользователя и управление сессией
    Route::get('/user', [UserController::class, 'userAPI']);
    Route::get('/logout', [UserController::class, 'logoutAPI']);
    Route::post('/updateProfile', [UserController::class, 'updateProfile']);
    Route::post('/updatePassword', [UserController::class, 'updatePassword']);
    Route::get('/kladovsiks', [UserController::class, 'kladovsiksAPI']);

    // админ: управление пользователями
    Route::get('/users', [UserController::class, 'usersAPI']);
    Route::get('/managers', [UserController::class, 'managersAPI']);
    Route::post('/user/{id}/role', [UserController::class, 'changeUserRole']);

    // категории: список, создание, обновление, удаление
    Route::get('/categories', [CategoryController::class, 'categoriesAPI']);
    Route::post('/category', [CategoryController::class, 'addCategoryAPI']);
    Route::post('/category/{category}', [CategoryController::class, 'updateCategoryAPI']);
    Route::delete('/category/{category}', [CategoryController::class, 'deleteCategoryAPI']);

    // товары: список, создание, обновление, удаление
    Route::get('/products', [ProductController::class, 'productsAPI']);
    Route::post('/product', [ProductController::class, 'addProductAPI']);
    Route::post('/product/{product}', [ProductController::class, 'updateProductAPI']);
    Route::delete('/product/{product}', [ProductController::class, 'deleteProductAPI']);

    // операции: мои, все, создание, изменение статуса, назначение менеджера
    Route::get('/my_operations', [OperationController::class, 'myOperationsAPI']);
    Route::get('/operations', [OperationController::class, 'operationsAPI']);
    Route::post('/operation', [OperationController::class, 'addOperationAPI']);
    Route::post('/operation/status/{id}', [OperationController::class, 'statusAPI']);
    Route::post('/operation/assign/{id}', [OperationController::class, 'assignManagerAPI']);

    // отчёты (только админ): по менеджерам, кладовщикам, операциям, поставщикам
    Route::get('/reports/managers', [OperationController::class, 'reportsAPI']);
    Route::get('/reports/kladovsiks', [OperationController::class, 'kladovsiksReportAPI']);
    Route::get('/reports/operations', [OperationController::class, 'operationsPeriodReportAPI']);
    Route::get('/reports/suppliers', [SupplierController::class, 'report']);

    // экспорт в PDF (только админ): менеджеры, кладовщики, операции, поставщики
    Route::get('/pdf/manager/{managerId}', [OperationController::class, 'pdfReport']);
    Route::get('/pdf/kladovsik/{kladovsikId}', [OperationController::class, 'pdfKladovsikReport']);
    Route::get('/pdf/operations/{month}/{year}', [OperationController::class, 'pdfOperationsReport']);
    Route::get('/pdf/supplier/{id}', [SupplierController::class, 'pdfSupplierReport']);

    // поставщики: список (все), создание/обновление/удаление (только админ)
    Route::get('/suppliers', [SupplierController::class, 'index']);
    Route::post('/supplier', [SupplierController::class, 'store']);
    Route::post('/supplier/{supplier}', [SupplierController::class, 'update']);
    Route::delete('/supplier/{supplier}', [SupplierController::class, 'destroy']);
});