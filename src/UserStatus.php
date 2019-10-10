<?php

namespace Fligno\User;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;

class UserStatus
{
    public static function routes($controllers)
    {
        foreach($controllers as $controller => $key) {
            $link = $controller ? $key : Str::kebab(str_replace('Controller', '', $key));
            $controller = $controller ?: $key;

            Route::post("/{$link}/active/{id}", "{$controller}@activate")->name("{$link}.activation");
            Route::post("/{$link}/deactivate/{id}", "{$controller}@deactivate")->name("{$link}.deactivation");
            Route::post("/{$link}/toggle/{id}", "{$controller}@toggleStatus")->name("{$link}.toggle");
            Route::post("/{$link}/bulk/update", "{$controller}@bulkStatusUpdate")->name("{$link}.bulkUpdate");
        }
    }
}
