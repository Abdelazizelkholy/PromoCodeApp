<?php

use App\Http\Controllers\Api\Admin\PromoCodeController;
use Illuminate\Support\Facades\Route;


Route::controller(PromoCodeController::class)->group(function(){

    Route::post('promo_codes', 'create');

});
