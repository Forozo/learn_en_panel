<?php

use App\Http\Controllers\v1\CategoryController;
use App\Http\Controllers\v1\BookController;
use App\Http\Controllers\v1\EitaaCleanerController;
use Illuminate\Support\Facades\Route;

//Category
Route::get('/get_categories', [CategoryController::class, 'getCategories']);
Route::get('/clear_categories', [CategoryController::class, 'clearCategories']);
Route::post('/create_category', [CategoryController::class, 'createCategory']);
Route::post('/update_category', [CategoryController::class, 'updateCategory']);
Route::post('/active_category', [CategoryController::class, 'activeCategory']);

//Book
Route::get('/clear_books', [BookController::class, 'clearBooks']);
Route::get('/get_books', [BookController::class, 'getBooks']);
Route::post('/update_book', [BookController::class, 'updateBook']);
Route::post('/active_book', [BookController::class, 'activeBook']);
Route::post('/add_book', [BookController::class, 'addBook']);

// Eitaa Cleaner
Route::get('/show_ad', [EitaaCleanerController::class, 'showMyAd']);
