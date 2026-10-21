<?php

use App\Http\Controllers\Admin\{AuthController, DashboardController, ResourceController};
use App\Http\Controllers\SiteController;
use App\Http\Middleware\StaffOnly;
use App\Http\Controllers\SeoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PollController;
Route::get('/ping', fn () => response('ok'));
Route::post('/poll/{poll}/vote', [PollController::class, 'vote'])
    ->name('poll.vote')->whereNumber('poll')->middleware('throttle:20,1');
Route::get('/search/suggest', [SiteController::class, 'suggest'])->name('search.suggest')->middleware('throttle:60,1');
// ---------- Site public ----------
Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/search', [SiteController::class, 'search'])->name('search')->middleware('throttle:30,1');
Route::get('/tag/{slug}', [SiteController::class, 'tag'])->name('tag');
Route::get('/author/{user}', [SiteController::class, 'author'])->name('author')->whereNumber('user');
Route::delete('/comment/{comment}', [SiteController::class, 'destroyComment'])
    ->name('comment.destroy')->whereNumber('comment')->middleware('throttle:20,1');
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/feed', [SeoController::class, 'feed'])->name('feed');
Route::get('/robots.txt', [SeoController::class, 'robots']);
Route::get('/category/{slug}', [SiteController::class, 'category'])->name('category');
Route::get('/article/{slug}', [SiteController::class, 'article'])->name('article');
Route::post('/article/{article}/comment', [SiteController::class, 'comment'])->name('article.comment')->middleware('throttle:10,1');
Route::post('/article/{article}/like', [SiteController::class, 'like'])->name('article.like')->middleware('throttle:30,1');
Route::get('/blog/{id}', [SiteController::class, 'blog'])->name('blog.show');
Route::get('/team', [SiteController::class, 'team'])->name('team');
Route::get('/contact', [SiteController::class, 'contactPage'])->name('contact');
Route::post('/contact', [SiteController::class, 'contact'])->name('contact.send')->middleware('throttle:5,1');
Route::get('/subscribe', [SiteController::class, 'subscribePage'])->name('subscribe');
Route::post('/subscribe', [SiteController::class, 'subscribe'])->name('subscribe.store')->middleware('throttle:5,1');

// ---------- Administration ----------
Route::prefix('admin')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
    Route::post('login', [AuthController::class, 'login'])->name('admin.login.post')->middleware(['guest', 'throttle:10,1']);
    Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

    Route::middleware(['auth', StaffOnly::class])->name('admin.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::prefix('r/{resource}')->name('crud.')->group(function () {
            Route::get('/', [ResourceController::class, 'index'])->name('index');
            Route::get('create', [ResourceController::class, 'create'])->name('create');
            Route::post('/', [ResourceController::class, 'store'])->name('store');
            Route::get('{id}/edit', [ResourceController::class, 'edit'])->name('edit');
            Route::put('{id}', [ResourceController::class, 'update'])->name('update');
            Route::delete('{id}', [ResourceController::class, 'destroy'])->name('destroy');
        });
    });
});
