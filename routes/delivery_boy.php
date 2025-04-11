<?php

use App\Http\Controllers\DeliveryBoyController;

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {

 Route::get('/delivery-boy/configuration', [DeliveryBoyController::class,'configuration'])->name('delivery-boy.configuration');
 Route::get('/delivery-boy/cancel-request-list', [DeliveryBoyController::class,'cancel_request_list'])->name('delivery-boy.cancel_request_list');
 Route::get('/delivery-boy/payment-histories', [DeliveryBoyController::class,'payment_histories'])->name('delivery-boy.payment_histories');
 Route::get('/delivery-boy/collection-histories', [DeliveryBoyController::class,'collection_histories'])->name('delivery-boy.collection_histories');
 Route::post('/delivery-boy/order-collection', [DeliveryBoyController::class,'order_collection_form'])->name('delivery-boy.order-collection');
 Route::post('/delivery-boy/delivery-earning', [DeliveryBoyController::class,'delivery_earning_form'])->name('delivery-boy.delivery-earning');
 Route::post('/collection-from-delivery-boy', [DeliveryBoyController::class,'collection_from_delivery_boy'])->name('collection-from-delivery-boy');
 Route::post('/paid-to-delivery-boy',  [DeliveryBoyController::class,'paid_to_delivery_boy'])->name('paid-to-delivery-boy');
 Route::get('/delivery-boy/ban/{id}', [DeliveryBoyController::class,'delivery_boy_ban'])->name('delivery-boy.ban');

 Route::resource('/delivery-boy', DeliveryBoyController::class);
 
});
Route::get('/delivery-boy/deliveryboycollection', [DeliveryBoyController::class,'deliveryboycollection']);
//Delivery Boy Assign
Route::post('/orders/delivery-boy-assign', [DeliveryBoyController::class,'assign_delivery_boy'])->name('orders.delivery-boy-assign');
