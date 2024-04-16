<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::controller(PagesController::class)->group(function() {
    Route::get('/', 'index');
});

Route::controller(BlogController::class)->group(function() {
    Route::get('/blog', 'index');
    Route::get('/blog/read/{slug}', 'show');
});

Route::controller(BlogController::class)->group(function() {
    Route::get('/blog', 'index');
    Route::get('/blog/read/{slug}', 'show');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // The routes that can work only when authenticated
    // Blog
    Route::get('/blog/create', [BlogController::class, 'create']);
    Route::post('/blog/store', [BlogController::class, 'store']);
    Route::get('/blog/{slug}/edit', [BlogController::class, 'edit']);
    Route::put('/blog/{slug}', [BlogController::class, 'update']);
    Route::delete('/blog/{slug}', [BlogController::class, 'delete']);

    // Comment
    Route::post('/comment', [CommentController::class, 'store_comment'])->name('comment.store');
    Route::post('/reply', [CommentController::class, 'store_reply'])->name('reply.store');


    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
