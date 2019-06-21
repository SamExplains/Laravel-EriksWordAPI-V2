<?php

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

use App\Interval;
use Symfony\Component\Panther as Panth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/retrieve/{isodate}', 'WordController@returnStoredWord');
Route::get('/test', 'WordController@test');
//  $interval = Interval::all()->first();
//
//  if (is_null($interval)) {
//    Interval::create([
//      'interval'=> 6
//    ]);
//    $interval = Interval::all()->first();
//  }
//  dd($interval->interval);


/* Middleware applied at controller level for the Routes below */
Route::post('/exists/{date}', 'WordController@checkIfDateExist')->name('exists');
Route::post('/move/{word}', 'WordController@moveWord')->name('move');
Route::get('/suggest-word', 'WordController@suggestNewWord')->name('suggest');
Route::resources([
  'word' => 'WordController',
  'interval' => 'IntervalController',
]);