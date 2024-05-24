<?php

use App\Http\Controllers\v1\createController;
use App\Http\Controllers\v1\getController;
use App\Http\Controllers\v1\updateController;
use Illuminate\Support\Facades\Route;

Route::get('/get_books/{id?}', [getController::class, 'getBooks']);
Route::get('/get_categories', [getController::class, 'getCategories']);
Route::post('/add_book', [createController::class, 'addBook']);
Route::post('/create_category', [createController::class, 'createCategory']);
Route::post('/update_book', [updateController::class, 'updateBook']);
Route::post('/update_category', [updateController::class, 'updateCategory']);
Route::post('/active_book', [updateController::class, 'activeBook']);
Route::post('/active_category', [updateController::class, 'activeCategory']);
