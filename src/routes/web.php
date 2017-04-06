<?php

Route::group(['middleware' => ['web'], 'prefix' => config('admin.url'), 'as' => 'admin.articles'], function () {
    Route::group(['middleware' => ['auth.admin', 'auth.rules']], function () {
        Route::get('articles/trash', ['uses' => 'ArticlesAdminController@index', 'as' => '.trash']);
        Route::post('articles/restore/{id}', ['uses' => 'ArticlesAdminController@restore', 'as' => '.restore']);
        Route::resource('articles', 'ArticlesAdminController', [
            'names' => [
                'index' => '.index',
                'create' => '.create',
                'store' => '.store',
                'edit' => '.edit',
                'update' => '.update',
                'show' => '.show',
            ], 'except' => ['destroy']]);
        Route::delete('articles/destroy', ['uses' => 'ArticlesAdminController@destroy', 'as' => '.destroy']);
    });
});

Route::group(['prefix' => 'api', 'as' => 'api.articles'], function () {
    Route::get('articles', ['uses' => 'ArticlesController@index', 'as' => '.index']);
    Route::get('articles/{slug}', ['uses' => 'ArticlesController@show', 'as' => '.show']);
});