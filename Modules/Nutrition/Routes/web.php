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




Route::group(['middleware' =>['web', 'auth-checker'], 'prefix' => 'nutrition'], function() {

    Route::post('/addfoodname', 'NutritionController@addFoodName')->name('addfoodname');
    Route::delete('/deletefoodname/{fldid}', 'NutritionController@deleteFoodName')->name('deletefoodname');

    Route::post('/addcatefoodtype', 'NutritionController@addFoodType')->name('addfoodtype');
    Route::delete('/deletefoodtype/{fldid}', 'NutritionController@deleteFoodType')->name('deletefoodtype');

    Route::prefix('nutritionalinfo')->group(function() {
        Route::get('/', 'NutritionalInfoController@index')->name('nutritionalinfo');
        Route::post('/addfoodcontent', 'NutritionalInfoController@addFoodContent')->name('addfoodcontent');
        Route::get('/editfoodcontent/{fldfoodid}', 'NutritionalInfoController@editFoodContent')->name('editfoodcontent');
        Route::patch('/updatefoodcontent/{fldfoodid}', 'NutritionalInfoController@updateFoodContent')->name('updatefoodcontent');
        Route::delete('/deletefoodcontent/{fldfoodid}', 'NutritionalInfoController@deleteFoodContent')->name('deletefoodcontent');
    });

    Route::prefix('foodmixture')->group(function() {
       Route::get('/', 'FoodmixtureController@index')->name('foodmixture');
       Route::post('/foodcontentfromtype', 'FoodmixtureController@FoodContentfromType')->name('foodcontentfromtype');
       Route::post('/foodgroupsubmit', 'FoodmixtureController@FoodGroupSubmit')->name('foodgroupsubmit');
       Route::post('/foodmixturetable', 'FoodmixtureController@loadfoodmixturetablefrombutton')->name('foodmixturetable');
    });

    Route::prefix('foodrequirements')->group(function() {
        Route::get('/', 'FoodRequirementController@index')->name('foodrequirement');
       Route::post('/addnutrition', 'FoodRequirementController@addnutrition')->name('addnutrition');
    });
});
