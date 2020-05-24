<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'BookController@index')->middleware('auth');

Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/books/search', 'BookController@search');

Route::group(['middleware' => 'auth'], function () {
    // Categorieën
    Route::resource('categories', 'CategoryController')
        ->except(['destroy']);
    Route::get('categories/{category}/delete', 'CategoryController@destroy')->name('categories.destroy');

    //Boeken
    Route::resource('books', 'BookController')
        ->except(['destroy']);
    Route::get('books/{book}/delete', 'BookController@destroy')->name('books.destroy');
    Route::get('books/search', 'BookController@search')->name('books.search');
    Route::get('/books/{book}/sold', 'BookController@sold')->name('books.sold');
    Route::get('/books/{book}/unsold', 'BookController@unsold')->name('books.unsold');
});
