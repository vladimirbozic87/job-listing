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

Route::get('/', function () {
    return redirect()->route('jobs');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/jobs', 'JobsController@getJobs')->name('jobs');

Route::get('/job/{job_name}', 'JobsController@getJob')->name('job');

Route::get('/add-job', 'JobsController@getAddJob')
    ->name('get-add-job')
    ->middleware('auth');

Route::post('/add-job', 'JobsController@postAddJob')
    ->name('post-add-job')
    ->middleware('auth');

Route::get('/edit-job/{job_name}', 'JobsController@getEditJob')
    ->name('get-edit-job')
    ->middleware('auth');

Route::post('/edit-job/{job_name}', 'JobsController@postEditJob')
    ->name('post-edit-job')
    ->middleware('auth');

Route::post('/apply/{job_name}', 'JobsController@postApplyJob')
    ->name('post-apply-job')
    ->middleware('guest');

Route::get('/apply/{job_name}', 'JobsController@getApplyJob')
    ->name('get-apply-job')
    ->middleware('guest');

