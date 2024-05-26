<?php

use App\Http\Controllers\CommentsController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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



Auth::routes();


Route::middleware(['auth'])->group(function () {

    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->withoutMiddleware(['auth']);
    Route::get('/discussions/create', [PageController::class, 'create'])->name('discussions.create')->withoutMiddleware(['auth']);
    Route::post('/discussions/create', [PageController::class, 'store'])->name('discussions.store');
    Route::get('/discussions/{id}', [PageController::class, 'show'])->name('discussions.show')->withoutMiddleware(['auth']);
    Route::get('/discussions/edit/{id}', [PageController::class, 'edit'])->name('discussions.edit');
    Route::post('/discussions/edit/{id}', [PageController::class, 'update'])->name('discussions.update');
    Route::get('/discussions/{id}/delete', [PageController::class, 'destroy'])->name('discussions.delete');
    Route::get('/discussions/{id}/approve', [PageController::class, 'approve'])->name('discussions.approve');

    Route::post('/comments/post', [CommentsController::class, 'post'])->name('post.comment');
    Route::get('/comments/delete/{id}', [CommentsController::class, 'destory'])->name('delete.comment');
});
