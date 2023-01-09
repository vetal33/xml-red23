<?php

use Illuminate\Support\Facades\Auth;
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

/*Route::get('/', function () {
    return view('welcome');
});*/


Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

Route::middleware('auth')->group(function () {
    Route::get('/map', [\App\Http\Controllers\MapController::class, 'index'])->name('map.index');
    Route::get('/xml-validator', [\App\Http\Controllers\XmlValidatorController::class, 'index'])->name('xml-validator.index');
    Route::post('xml-validator/upload', [\App\Http\Controllers\XmlValidatorController::class, 'store'])->name('xml-validator.upload');
    Route::post('map/upload-json', [\App\Http\Controllers\MapController::class, 'uploadJson'])->name('map.upload-json');
    Route::post('xml-validator/structure-validate/{file}', [\App\Http\Controllers\XmlValidatorController::class, 'structureValidate'])->name('xml-validator.structure-validate');
    Route::post('xml-validator/geom-validate/{file}', [\App\Http\Controllers\XmlValidatorController::class, 'geomValidate'])->name('xml-validator.geom-validate');
    Route::get('xml-validator/export-errors/{file}', [\App\Http\Controllers\XmlValidatorController::class, 'printErrorsPdf'])->name('validator-xml.print-errors-pdf');
});

Route::name("admin.")->prefix("admin")->middleware('role:admin')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\HomeController::class, 'index'])->name('home.index');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::get('/roles', [\App\Http\Controllers\Admin\RoleController::class, 'index'])->name('role.index');
});




//Update User Details
Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');

Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');

//Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);
