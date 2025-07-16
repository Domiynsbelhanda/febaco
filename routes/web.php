<?php

use App\Filament\Resources\TeamResource\Pages\ViewTeamTransfers;
use App\Http\Controllers\Api\PublicDataController;
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

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/public-data', [PublicDataController::class, 'index']);

Route::get('/admin/teams/{record}/transferts', ViewTeamTransfers::class)
    ->name('filament.admin.resources.teams.view-team-transfers');
