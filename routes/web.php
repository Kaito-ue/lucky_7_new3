<?php
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ManufacturerController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('products.index');
    } else {
        return redirect()->route('login');
    }
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::resource('products', ProductController::class);
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/home', [HomeController::class, 'index'])->name('home.index');
    Route::get('/my-dashboard', [HomeController::class, 'home'])->name('dashboard');
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
    Route::post('/manufacturers', [ManufacturerController::class, 'store'])->name('manufacturers.store');
    Route::get('/search', [ProductController::class, 'search'])->name('products.search');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
Route::post('/register', [RegisterController::class, 'register']);
