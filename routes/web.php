<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingsController;
use App\Http\Livewire\Chart;
use App\Http\Livewire\PurchaseDetails;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\ShoppingList;
use App\Http\Livewire\Statistics;
use App\Http\Livewire\Timeline;
use App\Http\Livewire\Toplist;

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

Route::redirect('/', 'index');

Route::get('index', Dashboard::class)
    ->name('home');
Route::get('settings', [SettingsController::class, 'index'])
    ->name('settings');
Route::get('purchase/statistics', Statistics::class)
    ->name('statistics');
Route::get('purchase/chart', Chart::class)
    ->name('chart');
Route::get('purchase/timeline', Timeline::class)
    ->name('timeline');
Route::get('purchase/top-list', Toplist::class)
    ->name('toplist');
Route::get('shopping-list', ShoppingList::class)
    ->name('shopping-list');
Route::get('purchase/{purchase}', PurchaseDetails::class)
    ->where('purchase', '[0-9]+')
    ->name('purchase');