<?php

use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\CarouselController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FeaturesFacilitiesController;
use App\Http\Controllers\Admin\RoomController as AdminRoomController;
use App\Http\Controllers\Admin\RoomSettingController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\UserQueryController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix('my')->middleware('auth')->group(function () {
    Route::get('/', [UserController::class, 'show'])->name('user.show');
    Route::post('/', [UserController::class, 'update'])->name('user.update');
    Route::post('/avatar', [UserController::class, 'updateAvatar'])->name('user.updateAvatar');
    Route::post('/password', [UserController::class, 'changePassword'])->name('user.changePassword');
    Route::get('/bookings', [UserController::class, 'bookings'])->name('user.bookings');
});

Route::prefix('ajax')->group(function () {
    Route::get('rooms', [AjaxController::class, 'rooms'])->name('ajax.rooms');
    Route::post('register', [AjaxController::class, 'register'])->name('ajax.register');
    Route::post('login', [AjaxController::class, 'login'])->name('ajax.login');
    Route::post('check-availability', [AjaxController::class, 'checkAvailability'])->name('ajax.checkAvailability');
});

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
Route::get('/room', [RoomController::class, 'show'])->name('rooms.detail');
Route::get('/facilities', [HomeController::class, 'facilities'])->name('home.facilities');
Route::get('/contact', [HomeController::class, 'contact'])->name('home.contact');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('home.submitContact');
Route::get('/about', [HomeController::class, 'about'])->name('home.about');
Route::get('/confirm-booking', [BookingController::class, 'confirm'])->name('booking.confirm');
Route::post('check-availability', [BookingController::class, 'checkAvailability'])->name('booking.checkAvailability');
Route::post('/pay-now', [BookingController::class, 'payNow'])->name('booking.payNow');
Route::post('/cancel-booking', [BookingController::class, 'cancel'])->name('booking.cancel');


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'login'])->name('index');
    Route::post('/login', [DashboardController::class, 'handleLogin'])->name('login');


    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::prefix('rooms')->name('rooms.')->group(function () {
        Route::get('/', [AdminRoomController::class, 'index'])->name('index');
        Route::post('/list', [AdminRoomController::class, 'list'])->name('list');
        Route::post('/add-number', [AdminRoomController::class, 'addNumber'])->name('addNumber');
        Route::post('/delete-number', [AdminRoomController::class, 'deleteNumber'])->name('deleteNumber');
    });

    Route::prefix('room-config')->name('roomSettings.')->group(function () {
        Route::get('/', [RoomSettingController::class, 'index'])->name('index');
        Route::post('/add_room', [RoomSettingController::class, 'add_room'])->name('add_room');
        Route::post('/get_all_rooms', [RoomSettingController::class, 'get_all_rooms'])->name('get_all_rooms');
        Route::post('/edit_details', [RoomSettingController::class, 'edit_details'])->name('edit_details');
        Route::post('/edit_room', [RoomSettingController::class, 'edit_room'])->name('edit_room');
        Route::post('/toggle_status', [RoomSettingController::class, 'toggle_status'])->name('toggle_status');
        Route::post('/add_image', [RoomSettingController::class, 'add_image'])->name('add_image');
        Route::post('/get_room_images', [RoomSettingController::class, 'get_room_images'])->name('get_room_images');
        Route::post('/rem_image', [RoomSettingController::class, 'rem_image'])->name('rem_image');
        Route::post('/thumb_image', [RoomSettingController::class, 'thumb_image'])->name('thumb_image');
        Route::post('/remove_room', [RoomSettingController::class, 'remove_room'])->name('remove_room');
        Route::post('/get_room', [RoomSettingController::class, 'get_room'])->name('get_room');
    });

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::post('/list', [AdminUserController::class, 'list'])->name('list');
        Route::post('/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('toggleStatus');
        Route::post('/remove', [AdminUserController::class, 'remove'])->name('remove');
        Route::post('/search', [AdminUserController::class, 'search'])->name('search');
    });

    Route::prefix('usersQuery')->name('usersQuery.')->group(function () {
        Route::get('/', [UserQueryController::class, 'index'])->name('index');
        Route::get('/seen', [UserQueryController::class, 'seen'])->name('seen');
        Route::get('/delete', [UserQueryController::class, 'delete'])->name('delete');
    });

    Route::prefix('FeaturesFacilities')->name('FeaturesFacilities.')->group(function () {
        Route::get('/', [FeaturesFacilitiesController::class, 'index'])->name('index');
        Route::post('/add_feature', [FeaturesFacilitiesController::class, 'add_feature'])->name('add_feature');
        Route::post('/get_features', [FeaturesFacilitiesController::class, 'get_features'])->name('get_features');
        Route::post('/rem_feature', [FeaturesFacilitiesController::class, 'rem_feature'])->name('rem_feature');
        Route::post('/add_facility', [FeaturesFacilitiesController::class, 'add_facility'])->name('add_facility');
        Route::post('/get_facilities', [FeaturesFacilitiesController::class, 'get_facilities'])->name('get_facilities');
        Route::post('/rem_facility', [FeaturesFacilitiesController::class, 'rem_facility'])->name('rem_facility');
    });

    Route::prefix('carousels')->name('carousels.')->group(function () {
        Route::get('/', [CarouselController::class, 'index'])->name('index');
        Route::post('/add_image', [CarouselController::class, 'add_image'])->name('add_image');
        Route::post('/get_carousel', [CarouselController::class, 'get_carousel'])->name('get_carousel');
        Route::post('/rem_image', [CarouselController::class, 'rem_image'])->name('rem_image');
    });

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::post('/get_general', [SettingController::class, 'get_general'])->name('get_general');
        Route::post('/upd_shutdown', [SettingController::class, 'upd_shutdown'])->name('upd_shutdown');
        Route::post('/upd_general', [SettingController::class, 'upd_general'])->name('upd_general');
        Route::post('/get_contacts', [SettingController::class, 'get_contacts'])->name('get_contacts');
        Route::post('/upd_contacts', [SettingController::class, 'upd_contacts'])->name('upd_contacts');
        Route::post('/add_member', [SettingController::class, 'add_member'])->name('add_member');
        Route::post('/get_members', [SettingController::class, 'get_members'])->name('get_members');
        Route::post('/rem_member', [SettingController::class, 'rem_member'])->name('rem_member');
    });

    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/list', [AdminBookingController::class, 'index'])->name('index');
        Route::post('/get_list', [AdminBookingController::class, 'get_list'])->name('get_list');
        Route::get('/generatePdf', [AdminBookingController::class, 'generatePdf'])->name('generatePdf');

        Route::get('/check_in', [AdminBookingController::class, 'check_in'])->name('check_in');
        Route::post('/get_bookings', [AdminBookingController::class, 'get_bookings'])->name('get_bookings');
        Route::post('/kh_booking', [AdminBookingController::class, 'kh_booking'])->name('kh_booking');
        Route::post('/huy_booking', [AdminBookingController::class, 'huy_booking'])->name('huy_booking');

        Route::get('/check_out', [AdminBookingController::class, 'check_out'])->name('check_out');
        Route::post('/change_room', [AdminBookingController::class, 'change_room'])->name('change_room');
        Route::post('/get_bookings_checkout', [AdminBookingController::class, 'get_bookings_checkout'])->name('get_bookings_checkout');
        Route::post('/payment_booking', [AdminBookingController::class, 'payment_booking'])->name('payment_booking');
        Route::post('/cancel_booking', [AdminBookingController::class, 'cancel_booking'])->name('cancel_booking');
    });
});
