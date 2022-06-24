<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;

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

Route::view('/', 'welcome');

Blade::directive(
    'livewire', function ($expression) {
        return "<?php echo (new App\Livewire)->initialRender($expression) ?>";
    }
);
