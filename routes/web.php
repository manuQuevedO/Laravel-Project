<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\FacultadController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuPrincipalController;
use App\Http\Controllers\ModuloController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\RolMenuPrincipalController;
use App\Http\Controllers\UniversidadController;
use App\Http\Middleware\VerifySession;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

// Ruta de inicio
// Route::get('/', function () {
//     return view('layout');
// })->name('home');

Route::get('/', function () {
    if (Auth::check()) {
        // Si el usuario está autenticado, redirige a la página principal (por ejemplo, un dashboard)
        return view('layout');; // Cambia esta ruta según lo que desees mostrar después del login
    } else {
        // Si el usuario no está autenticado, redirige al login
        return redirect()->route('login');
    }
});

// Rutas de Autenticación
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas Protegidas por el Middleware 'verify'
Route::middleware(['verify'])->group(function () {
    // Rutas para Universidades y demás recursos
    Route::resource('universidades', UniversidadController::class);
    Route::resource('areas', AreaController::class);
    Route::resource('configuraciones', ConfiguracionController::class);
    Route::resource('facultades', FacultadController::class);
    Route::resource('modulos', ModuloController::class);
    Route::resource('menus_principales', MenuPrincipalController::class);
    Route::resource('menus', MenuController::class);
    Route::resource('roles', RolController::class);
    Route::resource('roles_menus_principales', RolMenuPrincipalController::class);
});
