<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
#これがないとTarget class [CartItemController] does not exist.
use App\Http\Controllers\CartItemController;

Route::group(['middleware' => ['auth']], function() {
    #Laravel8以降ではコントロールを呼び出す際に「完全修飾クラス名」を使用
    #Route::get('/', 'ItemController@index');だと"ItemController" does not exist が発生
    Route::get('/', [ItemController::class, 'index']);
    Route::get('/item/{item}', [ItemController::class, 'show']);
    Route::get('/cartitem', [CartItemController::class, 'index']);
    Route::post('/cartitem', [CartItemController::class, 'store']);
    Route::get('/dashboard', function () {
        return view('dashboard');
     })->middleware(['auth', 'verified'])->name('dashboard'); 
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
