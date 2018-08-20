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

// mockup routes
// Route::get('/resident-monthly-report', function() {
//     return view('/resident-monthly-report/individualReportMockup');
// });
// Route::get('/scheduled-activity', function () {
//     return view('scheduled-activity/showMockup');
// });
// Route::get('/activities-list', function() {
//     return view('/activities-list/showMockup');
// });
Route::get('/schedule-an-activity', function() {
    return view('/schedule-an-activity/showMockup');
});
// Route::get('/residents', function() {
//     return view('/residents/showMockup');
// });

// --------------------------------------
Auth::routes();

Route::group(['middleware' => ['web']], function() {
    // app routes
    Route::get('/', function () {
        return view('welcome');
    });


    Route::get('/home',                             'HomeController@index')->name('home');
    Route::post('/home/{location}',                 'HomeController@store');

    Route::get('/activities',                       'ActivitiesController@index');
    Route::post('/activities',                      'ActivitiesController@store');
    Route::patch('/activities/{activity}',          'ActivitiesController@update');
    Route::delete('/activities/{activity}',         'ActivitiesController@destroy');


    Route::get('/domains',                          'DomainsController@index');
    Route::post('/domains',                         'DomainsController@store');
    Route::patch('/domains/{domain}',               'DomainsController@update');
    Route::delete('/domains/{domain}',              'DomainsController@destroy');

    //-----------------------
    // trying something a little different with individual activities
    // view returned for initial load - just list of residents and activities
    Route::get('/individual-activities',            'IndividualActivitiesController@index');
    // api for axios calls - attendance based on date for section at below
    // that shows any individual activities already scheduled for date
    // ...(building test for flesh this out)
    Route::post('/individual-activity-attendance',  'IndividualAttendanceRecordsController@show');
    Route::delete('/individual-activity-attendance/{individualAttendanceRecord}',  'IndividualAttendanceRecordsController@destroy');
    // new record: not sure about my naming convention
    Route::post('/individual-activity-attendance/store', 'IndividualAttendanceRecordsController@store');
    //-----------------------

    Route::get('/schedule-activities',              'ScheduleActivitiesController@index');
    // api-ish
    Route::post('/schedule-activities/{date}',      'ScheduleActivitiesController@store');
    Route::patch('/schedule-activities/{date}',     'ScheduleActivitiesController@update');
    Route::delete('/schedule-activities/{date}',    'ScheduleActivitiesController@destroy');
    Route::get('/schedule-activities-already-scheduled-list/{date}', 'ScheduleActivitiesAlreadyScheduledListController@index');

    Route::get('/scheduled-activities',             'ScheduledActivitiesController@index');
    // Route::get('/scheduled-activities/{id}',    'ScheduledActivitiesController@show');

    Route::get('/residents',                        'ResidentsController@index');
    Route::post('/residents',                       'ResidentsController@store');
    Route::patch('/residents/{resident}',           'ResidentsController@update');
    Route::delete('/residents/{resident}',          'ResidentsController@destroy');

    // api-esque routes
    Route::delete('/residents/{resident}/activities/{scheduledActivity}',   'ScheduledActivitiesController@destroy');
    Route::post('/residents/{resident}/activities/{scheduledActivity}',     'ScheduledActivitiesController@store');

    Route::post('/residents-and-attendance-records', 'AttendanceRecordsController@show');

    Route::get('/reports',                          'ReportsController@index');
    // api-ish
    Route::post('/reports',                         'ReportsController@show');
    // since this is single view being upated, the store method will
    // handle create, update and delete...
    Route::post('/reports/comment',                 'ReportsController@store');
});
