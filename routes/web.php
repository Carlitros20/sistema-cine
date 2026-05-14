<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminSessionController;
use App\Http\Controllers\AdminMovieController;
use App\Http\Controllers\AdminRoomController;
use App\Http\Controllers\AdminStatsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\SeatLockController;
use App\Http\Controllers\RatingController;
use App\Http\Middleware\IsAdmin;

// ─── Públicas ────────────────────────────────────────────────────────────────
Route::get('/',                 [MovieController::class, 'index'])->name('cartelera');
Route::get('/pelicula/{movie}', [MovieController::class, 'showMovie'])->name('movies.show');
Route::get('/sesion/{session}', [MovieController::class, 'show'])->name('sessions.show');
Route::get('/contacto',         [MovieController::class, 'contacto'])->name('contacto');
Route::get('/contacto.php', [MovieController::class, 'contacto']);

// ─── Auth ────────────────────────────────────────────────────────────────────
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login']);
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');
Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
Route::post('/registro',[AuthController::class, 'register']);

// ─── Usuario autenticado ──────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/ticket/buy',                 [MovieController::class, 'buyTicket'])->name('tickets.buy');
    Route::get('/ticket/confirmacion',         [MovieController::class, 'confirmacion'])->name('tickets.confirmacion');
    Route::delete('/ticket/{ticket}/devolver', [MovieController::class, 'devolverTicket'])->name('tickets.devolver');

    Route::get('/perfil', [ProfileController::class, 'index'])->name('profile.index');

    Route::post('/favorito/{movie}',   [FavoriteController::class, 'toggle'])->name('favorites.toggle');

    Route::post('/seat-lock',          [SeatLockController::class, 'lock'])->name('seats.lock');
    Route::delete('/seat-lock',        [SeatLockController::class, 'unlock'])->name('seats.unlock');
    Route::post('/seat-lock/release',    [SeatLockController::class, 'unlock'])->name('seats.release');
    Route::get('/seat-lock/{session}', [SeatLockController::class, 'status'])->name('seats.status');

    // Valoraciones
    Route::post('/pelicula/{movie}/rating',   [RatingController::class, 'store'])->name('ratings.store');
    Route::delete('/pelicula/{movie}/rating', [RatingController::class, 'destroy'])->name('ratings.destroy');
});

// ─── Administrador ───────────────────────────────────────────────────────────
Route::middleware(['auth', IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/sessions',                [AdminSessionController::class, 'index'])->name('sessions.index');
    Route::get('/sessions/create',         [AdminSessionController::class, 'create'])->name('sessions.create');
    Route::post('/sessions',               [AdminSessionController::class, 'store'])->name('sessions.store');
    Route::get('/sessions/{session}/edit', [AdminSessionController::class, 'edit'])->name('sessions.edit');
    Route::put('/sessions/{session}',      [AdminSessionController::class, 'update'])->name('sessions.update');
    Route::delete('/sessions/{session}',   [AdminSessionController::class, 'destroy'])->name('sessions.destroy');

    Route::get('/movies',                  [AdminMovieController::class, 'index'])->name('movies.index');
    Route::get('/movies/create',           [AdminMovieController::class, 'create'])->name('movies.create');
    Route::post('/movies',                 [AdminMovieController::class, 'store'])->name('movies.store');
    Route::get('/movies/{movie}/edit',     [AdminMovieController::class, 'edit'])->name('movies.edit');
    Route::put('/movies/{movie}',          [AdminMovieController::class, 'update'])->name('movies.update');
    Route::delete('/movies/{movie}',       [AdminMovieController::class, 'destroy'])->name('movies.destroy');

    Route::get('/rooms',                   [AdminRoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/create',            [AdminRoomController::class, 'create'])->name('rooms.create');
    Route::post('/rooms',                  [AdminRoomController::class, 'store'])->name('rooms.store');
    Route::get('/rooms/{room}/edit',       [AdminRoomController::class, 'edit'])->name('rooms.edit');
    Route::put('/rooms/{room}',            [AdminRoomController::class, 'update'])->name('rooms.update');
    Route::delete('/rooms/{room}',         [AdminRoomController::class, 'destroy'])->name('rooms.destroy');

    // Estadísticas
    Route::get('/stats', [AdminStatsController::class, 'index'])->name('stats.index');
    
});
