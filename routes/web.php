<?php

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

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/preview', 'PreviewerController@index')->name('previewer.index');
Route::post('/preview', 'PreviewerController@store')->name('previewer.store');

Route::get('/notes', 'NoteController@index')->name('note.index');
Route::get('/notes/list', 'NoteController@list')->name('note.list');
Route::post('/notes', 'NoteController@store')->name('note.store');
Route::patch('/notes/{id}/color', 'NoteController@updateColor')
            ->where('id', '[0-9]+')
            ->name('note.update-color');

Route::delete('/notes/{id}', 'NoteController@delete')
            ->where('id', '[0-9]+')
            ->name('note.delete');
