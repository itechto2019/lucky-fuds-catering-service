<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UserController;
use App\Models\Package;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;




Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // ADMIN
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // reservation
    Route::get('/reservation/schedule-events', [AdminController::class, 'ScheduleEvents'])->name('schedule_events');

    Route::get('/reservation/schedule-reservation',[AdminController::class, 'ScheduleReservation'])->name('schedule_reservation');
    Route::get('/reservation/schedule-reports',[AdminController::class, 'ScheduleReports'])->name('schedule_reports');
    Route::get('/get-event/{id}', [ScheduleController::class, 'getEvent'])->name('getEvent');


    // inventory
    Route::get('/inventory/stocks', [AdminController::class, 'InventoryStocks'])->name('inventory_stocks');
    Route::get('/rentals/extend-request', [AdminController::class, 'ExtendRequest'])->name('extend_request');
    Route::get('/rentals/for-rents',[AdminController::class, 'ForRents'])->name('inventory_for_rents');
    Route::get('/rentals/rents', [AdminController::class, "ForRented"])->name('inventory_rents');
    Route::get('/rentals/approves',[AdminController::class, 'Approves'])->name('inventory_approves');
    Route::get('/rentals/return', [AdminController::class, 'Returned'])->name('inventory_return');
    Route::get('/rentals/extends', [AdminController::class, 'Extends'])->name('inventory_extends');
    Route::get('/rentals/reports', [AdminController::class, 'Reports'])->name('inventory_reports');
    
    // Add Package
    Route::post('/add-package', [PackageController::class, 'createPackage'])->name('add_package');

    // Stocks
    Route::post('/add-supply', [StockController::class, 'createSupply'])->name('add_supply');
    Route::put('/set-rent/{id}', [StockController::class, 'toRent'])->name('to_rent');
    Route::delete('/delete-supply/{id}', [StockController::class, 'deleteSupply'])->name('delete_supply');
    Route::put('/add-to-return/{id}', [StockController::class, 'toReturn'])->name('to_return');
    Route::put('/update-supply/{id}', [StockController::class, 'updateSupply'])->name('to_update');

    // Reservation
    Route::put('/reserve-to-approve/{id}', [ScheduleController::class, 'ApproveReserve'])->name('to_approve');
    Route::put('/reserve-to-reject/{id}', [ScheduleController::class, 'RejectReserve'])->name('to_reject_reserve');
    

    // Approve / Decline
    Route::put('/add-to-checkout/{id}', [StockController::class, 'toCheckOut'])->name('to_checkout');
    Route::put('/add-to-rejected/{id}', [StockController::class, 'toReject'])->name('to_reject');

    // Returns
    Route::post('/add-to-items/{id}', [StockController::class, 'addToItems'])->name('add_to_items');
    Route::post('/add-to-rents/{id}', [StockController::class, 'addToRents'])->name('add_to_rents');

    // Reports
    // Route::get('/export-reports', [PrintController::class, 'InventoryReport'])->name('export_inventory');
    Route::get('/reservation-reports/{id}', [PrintController::class, 'ReservationReport'])->name('export_reservation');
    Route::get('/reservation-reports-download/{id}', [PrintController::class, 'ReservationReportDownload'])->name('download_reservation');
    
    
    Route::get('/rentals-reports/{id}', [PrintController::class, 'InventoryReport'])->name('export_inventory');
    Route::get('/rentals-reports-download/{id}', [PrintController::class, 'InventoryReportDownload'])->name('download_report');

    Route::get('/account/request', [AdminController::class, 'User'])->name('account_request');
    Route::get('/account/verified', [AdminController::class, 'verified'])->name('account_verified');

    Route::patch('/account/request/confirm/{id}', [AdminController::class, 'confirmVerification'])->name('confirm_verification');
    Route::patch('/account/request/reject/{id}', [AdminController::class, 'rejectVerification'])->name('reject_verification');

    Route::put('/edit-package/{id}', function (Request $request, $id) {
        $form = $request->validate([
            'name' => "min:8",
            'details' => "min:8",
            'price' => "min:1"
        ]);
        Package::where('id', $id)->update([
            'name' => $form['name'],
            'details' => $form['details'],
            'price' => $form['price']
        ]);
        return back();
    })->name('edit_package');
    Route::delete('/delete-package/{id}',  function ($id) {
        Package::where('id', $id)->delete();
        return back();
    })->name('delete_package');

});

Route::middleware(['auth', 'user', 'verified'])->prefix('user')->group(function () {
    // USER
    Route::get('/dashboard', [UserController::class, 'index'])->name('user_dashboard');

    // reservation
    Route::get('/reservation/schedule-events', [UserController::class, 'ScheduleEvents'])->name('user_schedule_events');
    Route::get('/reservation/schedule-reservation',[UserController::class, 'ScheduleReservation'])->name('user_schedule_reservation');
    Route::get('/reservation/confirmation-request',[UserController::class, 'ConfirmationRequest'])->name('user_schedule_confirmation');
    Route::get('/reservation/summary',[UserController::class, 'ReservationSummary'])->name('user_reseravation_summary');

    Route::post('/reservation/reserve-schedule-event', [ScheduleController::class, 'CreateEvent'])->name('user_create_event');
    Route::get('/get-event/{id}', [ScheduleController::class, 'getEvent'])->name('user_getEvent');

    
    // inventory
    Route::get('/inventory/for-rents', [UserController::class, 'ForRents'])->name('user_inventory_for_rents');
    Route::get('/inventory/rented', [UserController::class, 'Rented'])->name('user_inventory_rents');
    Route::get('/inventory/extend', [UserController::class, 'Extends'])->name('user_inventory_extends');
    Route::get('/inventory/summary', [UserController::class, 'Summary'])->name('user_inventory_summary');

    // Account
    Route::get('/account/profile', [UserController::class, 'AccountProfile'])->name('user_account_profile');
    Route::patch('/account/profile/update', [UserController::class, 'UpdateProfile'])->name('user_profile_update');
    Route::patch('/account/profile/validate', [UserController::class, 'validateId'])->name('user_validate_update');

    

    // User Rent
    Route::post('/user-rent/{rent_id}/{id}', [StockController::class, 'userRent'])->name('user_rent');
    
    // Extends
    Route::post('/rent-extends/{id}', [StockController::class, 'userExtends'])->name('user_extends');
});
Route::get('/', function () {
    if(Auth::check()) {
        if (Auth::user()->is_admin) {
            return redirect()->intended(route('dashboard'));
        } else {
            return redirect()->intended(route('user_dashboard'));
        }
    } else {
        return view('auth.login');
    }
});
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'register'])->name('register');

Route::post('/signin', [AuthController::class, 'signin'])->name('signin');
Route::post('/signup', [AuthController::class, 'signup'])->name('signup');
Route::get('/signout', [AuthController::class, 'logout'])->name('signout')->middleware(['auth']);

// Route::get('/verify', function () {
//     // $verifyToken = Str::random(100);
//     // $user = Auth::user();
//     // Mail::send('user.mail', ['verifyToken' => $verifyToken, 'user' => $user], function ($m) use($user) {
//     //     $m->from(env('MAIL_USERNAME'), 'Lucky Fuds Service Catering System');
//     //     $m->to($user->email)->subject('Lucky Fuds Service Catering System | Verification');
//     // });
//     // return view('user.verification')->with(compact(['verifyToken', 'user']));
    
// })->name('verify_first');

// Route::get('/verified', function () {
    
// })->name('verification.verify');

Route::get('/terms', function () {
    return view('terms');
})->name('terms');
Route::get('/policy', function () {
    return view('policy');
})->name('policy');

