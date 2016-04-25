<?php

Route::group(['prefix' => config('admin.url')], function () {
    Route::group(['middleware' => ['auth.admin', 'auth.rules']], function () {
        Route::get('articles/trash', ['uses' => 'ArticlesAdminController@index', 'as' => 'admin.articles.trash']);
        Route::post('articles/restore/{id}', ['uses' => 'ArticlesAdminController@restore', 'as' => 'admin.articles.restore']);
        Route::resource('articles', 'ArticlesAdminController', [
            'names' => [
                'index' => 'admin.articles.index',
                'create' => 'admin.articles.create',
                'store' => 'admin.articles.store',
                'edit' => 'admin.articles.edit',
                'update' => 'admin.articles.update',
                'show' => 'admin.articles.show',
            ], 'except' => ['destroy']]);
        Route::delete('articles/destroy', ['uses' => 'ArticlesAdminController@destroy', 'as' => 'admin.articles.destroy']);
    });
});