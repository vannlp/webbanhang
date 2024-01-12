<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\Site\Http\Controllers\SiteController;
use Tymon\JWTAuth\Facades\JWTAuth;

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

Route::get('/profile', [UserController::class, 'profile'])->name('profile')->middleware('auth_web');

Route::get('/logout', [AuthController::class, 'logout']);



Route::prefix('/admin')->name("admin.")
    // ->middleware('admin')
    ->group(function() {
    Route::get('/login', [AuthController::class, 'login'])->withoutMiddleware('admin');
    Route::post('/handel-login', [AuthController::class, 'handleLogin'])->name('handle_login');
    Route::get('/', [DashboardController::class, 'index'])->middleware('auth_web');
    Route::get('/user', [UserController::class, 'index'])
        ->name('user.index')
        ->middleware('auth_web:LISTUSER');

    Route::post('/user/create', [UserController::class, 'create'])->name('user.create')->middleware('auth_web:CREATEUSER');

    Route::put('/user/edit/{id}', [UserController::class, 'edit'])->name('user.edit')->middleware('auth_web:UPDATEUSER');

    Route::get('/role', [RolePermissionController::class, 'index'])->name('role')->middleware('auth_web:LISTROLE');
    Route::get('/role/edit/{id}', [RolePermissionController::class, 'editRole'])->name('role.edit')->middleware('auth_web');
    Route::put('/role/handleEdit/{id}', [RolePermissionController::class, 'handleEditRole'])->name('role.handleEdit')->middleware('auth_web');
    Route::get('/permission' , [RolePermissionController::class, 'indexPermission'])->name('permission')->middleware('auth_web:LISTPERMISSION');
    Route::post('/role/create', [RolePermissionController::class, 'createRole'])->name('role.create')->middleware('auth_web:ADDPERMISSION');
    Route::delete('/permission/delete', [RolePermissionController::class, 'delete'])->name('permission.delete')->middleware('auth_web');
    Route::post('/permission/group/create', [RolePermissionController::class, 'createGroup'])->name('permission.group.create')->middleware('auth_web');
    Route::post('/permission/create', [RolePermissionController::class, 'createPermission'])->name('permission.create')->middleware('auth_web');
    Route::get('/user/get-one/{id}', [UserController::class, 'getOne'])->middleware('auth_web:LISTUSER')->name('api.user.get_one');


    // media
    Route::prefix('/media')->name('media.')->group(function() {
        Route::get('/', [FileController::class, 'index'])->middleware('auth_web:VIEW_MEDIA')->name('index');
        Route::get('/get-one/{id}',[FileController::class, 'getOne'])->middleware('auth_web:VIEW_MEDIA')->name('getOne');
        Route::put('/update/{id}', [FileController::class, 'handleUpdate'])->middleware('auth_web')->name('update');
        Route::post('/upload-images', [FileController::class, 'uploadImages'])->name('upload_images');
        // Route::post('/upload-image-ckeditor', [FileController::class, 'uploadFileCk'])->name('upload_ck');
    });

    // post
    Route::prefix('/post')->name('post.')->group(function () {
        Route::get('/category', [CategoryController::class, 'indexCategoryPost'])->middleware('auth_web:VIEW_CATEGORY_POST')->name('category');
        Route::post('/category/create', [CategoryController::class, 'createCategoryPost'])->middleware('auth_web:CREATE_CATEGORY_POST')->name('category.create');

        Route::get('/category/edit/{id}', [CategoryController::class, 'getCategoryPost'])->middleware('auth_web:VIEW_CATEGORY_POST')->name('category.edit');
        Route::put('/category/update/{id}', [CategoryController::class, 'updateCategoryPost'])->middleware('auth_web:EDIT_CATEGORY_POST')->name('category.update');
        Route::delete('/category/delete/{id}', [CategoryController::class, 'deleteCategoryPost'])->middleware('auth_web:DELETE_CATEGORY_POST')->name('category.delete');

        Route::get('/', [PostController::class, 'index'])->middleware('auth_web:VIEW_POST')->name('index');
        Route::get('/add', [PostController::class, 'create'])->middleware('auth_web:CREATE_POST')->name('add');
        Route::post('/handle-create', [PostController::class, 'handleCreate'])->middleware('auth_web:CREATE_POST')->name('handleCreate');
        Route::get('/edit/{id}', [PostController::class, 'edit'])->middleware('auth_web')->name('edit');
    });

    //product
    Route::prefix('/product')->name('product.')->group(function() {
        Route::get('/category', [CategoryController::class, 'indexCategoryProduct'])->middleware('auth_web:VIEW_PRODUCT_CATEGORY')->name('category');
        Route::post('/category/create', [CategoryController::class, 'createCategoryProduct'])->middleware('auth_web:CREATE_PRODUCT_CATEGORY')->name('category.create');
        Route::put('/category/update/{id}', [CategoryController::class, 'updateCategoryPost'])->middleware('auth_web:UPDATE_PRODUCT_CATEGORY')->name('category.update');
        Route::delete('/category/delete', [CategoryController::class, 'deleteCategoryProduct'])->middleware('auth_web:DELETE_PRODUCT_CATEGORY')->name('category.delete');
        Route::get('/category/edit/{id}', [CategoryController::class, 'getCategoryProduct'])->middleware('auth_web:VIEW_PRODUCT_CATEGORY')->name('category.edit');

        Route::get('/', [ProductController::class, 'index'])->middleware('auth_web:VIEW_PRODUCT')->name('index');
        Route::delete('/delete', [ProductController::class, 'delete'])->middleware('auth_web:DELETE_PRODUCT')->name('delete');
        Route::get('/create', [ProductController::class, 'create'])->middleware('auth_web:CREATE_PRODUCT')->name('create');
        Route::post('/store', [ProductController::class, 'store'])->middleware('auth_web:CREATE_PRODUCT')->name('store');
        Route::get('/edit/{id}', [ProductController::class, 'edit'])->middleware('auth_web:UPDATE_PRODUCT')->name('edit');
        Route::put('/update/{id}', [ProductController::class, 'update'])->middleware('auth_web:UPDATE_PRODUCT')->name('update');

    });
    
    // module
    Route::prefix('/module')->name('module.')->group(function() {
        Route::get('/', [ModuleController::class, 'index'])->name('index');
        Route::put('/update-status', [ModuleController::class, 'update'])->name('update');
    });
});
Route::post('/addToCart', [CartController::class, 'addToCartForClient'])->name('addToCartForClient');
Route::get('/cart', [SiteController::class, 'cartPage'])->name('cartPage');
Route::delete('/remove-cart-detail/{idCartDetail}', [CartController::class, 'removeCartDetail'])->name('removeCartDetail');
Route::put('/update-cart', [CartController::class, 'updateCart'])->name('updateCart');

Route::get('/get-cart', [CartController::class, 'getCartApi'])->name('getCartApi');

Route::get('/test', [TestController::class, 'index']);
Route::get('/test-post', [CategoryController::class, 'index']);
Route::put('/profile/edit', [UserController::class,'updateProfile'])->name('profile.edit')->middleware('auth_web');

Route::any('/ckfinder/connector', '\CKSource\CKFinderBridge\Controller\CKFinderController@requestAction')
    ->name('ckfinder_connector');

Route::any('/ckfinder/browser', '\CKSource\CKFinderBridge\Controller\CKFinderController@browserAction')
    ->name('ckfinder_browser');

// vendor/ckfinder/ckfinder-laravel-package/src/routes.php

Route::any('/ckfinder/examples/{example?}', 'CKSource\CKFinderBridge\Controller\CKFinderController@examplesAction')
    ->name('ckfinder_examples');

