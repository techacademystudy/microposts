<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UsersController;
use App\Http\Controllers\MicropostsController;
use App\Http\Controllers\UserFollowController;  // 追記
use App\Http\Controllers\FavoriteController;  // 追記

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

Route::get('/', [MicropostsController::class, 'index']);

Route::get('/dashboard', [MicropostsController::class, 'index'])->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::group(['middleware' => ['auth']], function () {
        Route::group(['prefix' => 'users/{id}'], function () {                                          // 追記
            Route::post('follow', [UserFollowController::class, 'store'])->name('user.follow');         // 追記
            Route::delete('unfollow', [UserFollowController::class, 'destroy'])->name('user.unfollow'); // 追記
            Route::get('followings', [UsersController::class, 'followings'])->name('users.followings'); // 追記
            Route::get('followers', [UsersController::class, 'followers'])->name('users.followers');    // 追記
            // Route::get('favorites', [UsersController::class, 'favorites'])->name('users.favorites');            // 追加

        });

    Route::group(['prefix' => 'microposts/{id}'], function () {
        Route::post('favorite', [FavoriteController::class, 'store'])->middleware(['auth'])->name('micropost.favorite');
        Route::delete('unfavorite', [FavoriteController::class, 'destroy'])->middleware(['auth'])->name('micropost.unfavorite');
    });                                                                                    
    
    Route::resource('users', UsersController::class, ['only' => ['index', 'show']]);
    Route::resource('microposts', MicropostsController::class, ['only' => ['store', 'destroy']]);
});
// 「該当ユーザーのお気に入り一覧を取得する」ログイン状態を問わない
Route::get('/users/{id}/favorites', [FavoriteController::class, 'favorites'])
    ->name('users.favorites');
    
// 「該当投稿をお気に入りしているユーザー一覧を取得する」ログイン状態を問わない
Route::get('/microposts/{id}/favorited_by', [FavoriteController::class, 'favorited_by'])
    ->name('micropost.favorited_by');