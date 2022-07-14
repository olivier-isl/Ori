<?php

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\LessonsController;
use App\Http\Controllers\OptionsController;
use App\Http\Controllers\Lessons_coursesController;
use App\Http\Controllers\QuizzsController;
use App\Http\Controllers\SearchController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

/*
 TODO: regrouper l'ensemble par groupe de namespace 'Front' & 'Back' en plus d'exploiter un middleware de connection
*/

// // Options
Route::get('/options',[OptionsController::class, 'index'])->name('getOptions');
Route::get('/website',[OptionsController::class, 'get'])->name('getDataWebsite');
Route::put('/options', [OptionsController::class,'update'])->name('editOptions'); //* for auth

// // Users
Route::group(['prefix' => '/users'], function() {
    $controller = UserController::class;
    Route::get('/',[$controller, 'index'])->name('getUsers');
    Route::get('/{user}', [$controller, 'userByName'])->name('getUser');
});

// // Courses
Route::group(['prefix' => '/courses'], function() {
    $controller = CoursesController::class;
    Route::get('/', [$controller, 'index'])->name('getCourses');
    Route::post('/', [$controller, 'store'])->name('addCourse');  //* for auth
    Route::get('/{response}', [$controller, 'courseByPermalinkOrId'])->name('getCourse');
    Route::put('/{id}', [$controller, 'update'])->name('editCourse');  //* for auth
    Route::delete('/{id}', [$controller, 'delete'])->name('deleteCourse');  //* for auth
});

// Lessons
Route::group(['prefix' => '/lessons'], function() {
    $controller = LessonsController::class;
    Route::get('/',[$controller, 'index'])->name('getLessons');
    Route::post('/', [$controller,'store'])->name('addLessons');  //* for auth
    Route::get('/{response}', [$controller, 'lessonByNameOrId'])->name('getLesson');
    Route::put('/{id}', [$controller, 'update'])->name('editLesson');  //* for auth
    Route::delete('/{id}', [$controller, 'delete'])->name('deleteLesson');  //* for auth
});

// Lessons_courses
Route::group(['prefix' => '/lessons_courses'], function() {
    $controller = Lessons_coursesController::class;
    Route::get('/courses/{id}',[$controller, 'getByCourseId'])->name('getLessons_coursesbyCoursesId');
    Route::put('/courses/{id}',[$controller, 'updateByCourseId'])->name('updateLessons_coursesbyCoursesId'); //* for auth
    Route::delete('/courses/{id}',[$controller, 'deleteByCourseIdAndLessonId'])->name('deleteLessons_coursesbyCoursesId'); //* for auth

    Route::get('/lessons/{id}',[$controller, 'getByLessonId'])->name('getLessons_coursesbyLessonsId');
    Route::post('/',[$controller, 'storeByCourseId'])->name('addLessons_courses'); //* for auth
    Route::post('/many',[$controller, 'storeMany'])->name('addManyLessons_courses'); //* for auth
});



// Quizz
Route::group(['prefix' => '/quizz'], function() {
    $controller = QuizzsController::class;
    Route::get('/',[$controller, 'index'])->name('getQuizzs');
    Route::post('/', [$controller,'store'])->name('addQuizz');  //* for auth
    Route::get('/{response}', [$controller, 'quizzByNameOrId'])->name('getQuizz');

    Route::put('/{id}', [$controller, 'update'])->name('editQuizz');  //* for auth
});

// Route::get('statuscourses/{status}',[CoursesController::class, 'filterBystatus'])->name('statusCourses');
// Route::get('searchcourses/{word}',[CoursesController::class, 'search'])->name('searchCourses');

Route::get('search/{word}',[SearchController::class, 'search'])->name('search');



// // Route::group(['middleware' => ['auth:sanctum']], function () {	
	
// // });

Route::fallback(function(){
    return response()->json([
        'response' => 404,
        'message' => 'Page Not Found. If error persists, contact contact@myrage.be',
        'length'    => 0
    ],
    404);
});