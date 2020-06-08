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

//Player Login :
/*Route::get('/', 'TeamController@login');
Route::get('player/login', 'TeamController@firstMessage');
Route::post('player/checklogin', 'TeamController@checklogin');
Route::get('player/logout', 'TeamController@logout');
Route::get('player/message','TeamController@firstMessage');*/

Route::get('/', 'TeamController@home');

Route::get('player/login', 'TeamController@login');
Route::post('player/checklogin', 'TeamController@checklogin');
Route::get('player/logout', 'TeamController@logout');
Route::get('player/message','TeamController@firstMessage');
Route::get('player/endPage','TeamController@finishJourney');
// GameMaster Login :
Route::get('gm', 'GameMasterController@home');
Route::get('gm/login', 'GameMasterController@login');
Route::post('gm/checklogin', 'GameMasterController@checklogin');
Route::get('gm/logout', 'GameMasterController@logout');
Route::get('gm/startChrono','GameMasterController@startChrono');

// Messenger
Route::get('msg/list', 'MessengerController@listRooms');
Route::post('msg/send/{room}', 'MessengerController@sendMessage');
Route::get('msg/{room}', 'MessengerController@getMessages');
Route::get('msg/update/{room}','MessengerController@messageRead');

// Riddle
Route::get('riddle/list', 'RiddleController@listRiddles');
Route::get('riddle/{id}/start', 'RiddleController@startRiddle');
Route::get('riddle/{id}/cancel', 'RiddleController@cancelRiddle');
Route::get('validationEnigme', 'ValidationEnigmeController@Index');
Route::get('validationEnigme/validationMdp', 'ValidationMdpController@checkMdp');
Route::get('validationEnigme/validationMdp/{id}', 'ValidationMdpController@checkMdp');

// RiddlesTeams
Route::get('riddleteam/list', 'RiddleTeamController@listRiddlesTeams');
Route::get('riddleteam/fullList', 'RiddleTeamController@listAllRiddles');


// Player
Route::get('player/', 'PlayerController@home');
Route::get('player/login', 'TeamController@login');
Route::post('player/checklogin', 'TeamController@checklogin');
Route::get('player/logout', 'TeamController@logout');
Route::get('player/message','TeamController@firstMessage');
Route::get('player/startDate','TeamController@getStartDate');
//Route::get('player/classement','TeamController@classement');

//  Admin
Route::get('admin', 'AdminController@home');
Route::post('admin/modifyRiddle', 'AdminController@modifyRiddle');
Route::post('admin/refreshDB', 'AdminController@refreshDB');
Route::post('admin/addGM', 'AdminController@addGM');
Route::get('admin/logout', 'AdminController@logout');
Route::get('admin/login', 'GameMasterController@login');
