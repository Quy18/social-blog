<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// routes/web.php
Route::get('/test-gd', function () {
    return extension_loaded('gd') 
        ? 'GD ĐÃ BẬT UPLOAD + RESIZE ẢNH CHẠY NGON!' 
        : 'GD chưa bật';
});
