<?php

use App\Http\Controllers\Admin\AdminDashboardController;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\User\UserDashboardController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// routes/web.php
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
     Route::get('/sale/create', [UserDashboardController::class, 'saleCreate'])->name('saleCreate');
    Route::post('/sale/store', [UserDashboardController::class, 'saleStore'])->name('saleStore');
    Route::get('/sales', [UserDashboardController::class, 'saleList'])->name('saleList');
});

Route::middleware(['auth', 'admin'])
->prefix('admin')
->name('admin.')
->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('categories',[AdminDashboardController::class,'listOfCategory'])->name('categoryList');
    Route::get('/add-category',[AdminDashboardController::class,'addCategory'])->name('addCategory');
    Route::post('/store-category',[AdminDashboardController::class,'storeCategory'])->name('storeCategory');
    Route::get('/edit-category/{category}',[AdminDashboardController::class,'editCategory'])->name('editCategory');
    Route::put('/update-category/{category}',[AdminDashboardController::class,'updateCategory'])->name('updateCategory');
    Route::delete('/delete-category/{category}',[AdminDashboardController::class,'destroyCategory'])->name('deleteCategory');



    Route::get('/suppliers',[AdminDashboardController::class,'suppliersList'])->name('supplierList');
    Route::get('/add-suppliers',[AdminDashboardController::class,'supplierCreate'])->name('supplierCreate');
    Route::post('/add-suppliers',[AdminDashboardController::class,'supplierStore'])->name('supplierStore');
    Route::get('/edit-suppliers/{supplier}',[AdminDashboardController::class,'supplierEdit'])->name('supplierEdit');
    Route::put('/edit-suppliers/{supplier}',[AdminDashboardController::class,'supplierUpdate'])->name('supplierUpdate');
    Route::delete('/delete-suppliers/{supplier}',[AdminDashboardController::class,'supplierDelete'])->name('supplierDelete');



    Route::get('/brands', [AdminDashboardController::class, 'brandList'])->name('brandList');

    Route::get('/brand/create', [AdminDashboardController::class, 'brandCreate'])->name('brandCreate');
    Route::post('/brand/store', [AdminDashboardController::class, 'brandStore'])->name('brandStore');

    Route::get('/brand/edit/{brand}', [AdminDashboardController::class, 'brandEdit'])->name('brandEdit');
    Route::put('/brand/update/{brand}', [AdminDashboardController::class, 'brandUpdate'])->name('brandUpdate');

    Route::delete('/brand/delete/{brand}', [AdminDashboardController::class, 'brandDelete'])->name('brandDelete');


    Route::get('/products', [AdminDashboardController::class, 'productList'])->name('productList');

    Route::get('/product/create', [AdminDashboardController::class, 'productCreate'])->name('productCreate');
    Route::post('/product/store', [AdminDashboardController::class, 'productStore'])->name('productStore');

    Route::get('/product/edit/{product}', [AdminDashboardController::class, 'productEdit'])->name('productEdit');
    Route::put('/product/update/{product}', [AdminDashboardController::class, 'productUpdate'])->name('productUpdate');

    Route::delete('/product/delete/{product}', [AdminDashboardController::class, 'productDelete'])->name('productDelete');
    
    // inside the existing admin group, after products
    Route::get('/purchases', [PurchaseController::class, 'purchaseList'])->name('purchaseList');
    Route::get('/purchase/create', [PurchaseController::class, 'purchaseCreate'])->name('purchaseCreate');
    Route::post('/purchase/store', [PurchaseController::class, 'purchaseStore'])->name('purchaseStore');
    
    
    });








Route::middleware('auth')->group(function () {

   
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';
