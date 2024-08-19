<?php

use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\AppointmentStatusController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DashboardStatController;
use App\Http\Controllers\Admin\LostController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TvController;
use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('admin.layouts.app');
})->middleware('auth');

// Route::get('/admin/dashboard', function () {
//     return view('dashboard');
// });

Route::middleware('auth')->group(function () {
    Route::get('/api/stats/appointments', [DashboardStatController::class, 'appointments']);
    Route::get('/api/stats/users', [DashboardStatController::class, 'users']);
    Route::get('/api/stats/complaints', [DashboardStatController::class, 'complaints']);

    Route::get('/api/users', [UserController::class, 'index']);
    Route::post('/api/users/pdf', [UserController::class, 'pdf']);
    Route::post('/api/users', [UserController::class, 'store']);
    Route::patch('/api/users/{user}/change-role', [UserController::class, 'changeRole']);
    Route::put('/api/users/{user}', [UserController::class, 'update']);
    Route::delete('/api/users/{user}', [UserController::class, 'destory']);
    Route::delete('/api/users', [UserController::class, 'bulkDelete']);

    Route::get('/api/clients', [ClientController::class, 'index']);

    Route::get('/api/appointment-status', [AppointmentStatusController::class, 'getStatusWithCount']);
    Route::get('/api/appointments', [AppointmentController::class, 'index']);
    Route::post('/api/appointments/create', [AppointmentController::class, 'store']);
    Route::get('/api/appointments/{appointment}/edit', [AppointmentController::class, 'edit']);
    Route::put('/api/appointments/{appointment}/edit', [AppointmentController::class, 'update']);
    Route::delete('/api/appointments/{appointment}', [AppointmentController::class, 'destroy']);

    Route::get('/api/settings', [SettingController::class, 'index']);
    Route::post('/api/settings', [SettingController::class, 'update']);

    Route::get('/api/profile', [ProfileController::class, 'index']);
    Route::put('/api/profile', [ProfileController::class, 'update']);
    Route::post('/api/upload-profile-image', [ProfileController::class, 'uploadImage']);
    Route::post('/api/change-user-password', [ProfileController::class, 'changePassword']);

    Route::get('/api/getItems', [LostController::class, 'index']);
    Route::get('/api/getClaimedItems', [LostController::class, 'ClaimedIndex']);
    Route::get('/api/getlostcounts', [LostController::class, 'lostcount']);
    Route::post('/api/storeItem', [LostController::class, 'store']);
    Route::post('/api/updateItem', [LostController::class, 'update']);
    Route::post('/api/claimItem', [LostController::class, 'claim']);
    Route::post('/api/claimItems', [LostController::class, 'claimItems']);
    Route::post('/api/bulkDelete', [LostController::class, 'bulkDelete']);
    Route::post('/api/reportItems', [LostController::class, 'reportItems']);
    Route::delete('/api/deleteItem/{item}', [LostController::class, 'destroy']);

    Route::get('/api/getTvs', [TvController::class, 'index']);
    Route::post('/api/storeTv', [TvController::class, 'store']);
    Route::delete('/api/deleteTv/{item}', [TvController::class, 'destroy']);
    Route::post('/api/updateTv', [TvController::class, 'update']);


    Route::get('/api/getRooms', [RoomController::class, 'index']);
    Route::get('/api/selectedRooms', [RoomController::class, 'selectedrooms']);
    Route::get('/api/getRoom2', [RoomController::class, 'index2']);
    Route::post('/api/storeRoom', [RoomController::class, 'store']);
    Route::post('/api/storeRoom2', [RoomController::class, 'store2']);
    Route::post('/api/updateRoom/{id}', [RoomController::class, 'update']);


    Route::get('/api/getDepartments', [DepartmentController::class, 'index']);
    Route::get('/api/getDepartment2', [DepartmentController::class, 'index2']);
    Route::post('/api/storeDepartment', [DepartmentController::class, 'store']);

    Route::get('/api/getMembers', [MemberController::class, 'index']);
    Route::get('/api/getInactiveMembers', [MemberController::class, 'inactive']);
    Route::get('/api/getcomplaintmembers', [MemberController::class, 'complaintmember']);
    Route::get('/api/getcomplaintmember', [MemberController::class, 'complaintmember2']);
    Route::get('/api/getMember2/{id}', [MemberController::class, 'index2']);
    Route::post('/api/storeMember', [MemberController::class, 'store']);
    Route::post('/api/storeMemberdepartment', [MemberController::class, 'storebydepartment']);
    Route::post('/api/updateMember/{id}', [MemberController::class, 'update']);

    Route::post('/api/storeMessage', [MessageController::class, 'store']);
    Route::get('/api/getComplaintmessages/{id}', [MessageController::class, 'show']);
    Route::get('/api/getmessages', [MessageController::class, 'index']);
    Route::get('/api/getcounts', [MessageController::class, 'count']);

    Route::get('/api/getComplaints', [ComplaintController::class, 'index']);
    Route::get('/api/findComplaints', [ComplaintController::class, 'find']);
    Route::get('/api/sendDailyreport', [ComplaintController::class, 'dailyReport']);
    Route::get('/api/getComplaints/user', [ComplaintController::class, 'index_user']);
    Route::get('/api/getComplaints/service', [ComplaintController::class, 'index_service']);
    Route::get('/api/getcomplaints', [ComplaintController::class, 'index2']);
    Route::get('/api/getComplaints/{id}', [ComplaintController::class, 'show']);
    Route::get('/api/getMessagecount', [ComplaintController::class, 'count']);
    Route::get('/api/getcomplaintcounts', [ComplaintController::class, 'countcomplaint']);
    Route::get('/api/getAttachments', [ComplaintController::class, 'attachment']);
    Route::post('/api/storePhoto', [ComplaintController::class, 'storePhoto']);
    Route::post('/api/storeComplaint', [ComplaintController::class, 'store']);
    Route::post('/api/updateComplaint', [ComplaintController::class, 'update']);
    Route::post('/api/storeStatus', [ComplaintController::class, 'updateStatus']);
    Route::post('/api/reportComplaints', [ComplaintController::class, 'reportComplaints']);
    Route::post('/api/reportComplaintbyRoom', [ComplaintController::class, 'reportComplaintbyRoom']);
    Route::delete('/api/complaints/{id}', [ComplaintController::class, 'destroy']);
    Route::delete('/api/closecomplaints/{id}', [ComplaintController::class, 'close']);
    Route::delete('/api/attachment/{id}', [ComplaintController::class, 'deletephoto']);

    Route::get('/api/getCategories', [CategoryController::class, 'index']);
    Route::get('/api/getDepartmentcategories/{id}', [CategoryController::class, 'show']);
    Route::get('/api/selectedCategory', [CategoryController::class, 'selectedrooms']);
    Route::get('/api/getCategory2', [CategoryController::class, 'index2']);
    Route::post('/api/storeCategory', [CategoryController::class, 'store']);
    Route::post('/api/updateCategory', [CategoryController::class, 'update']);
    Route::delete('/api/departmentcategory/{id}', [CategoryController::class, 'unlink']);
    Route::post('/api/departmentCategory/{id}', [CategoryController::class, 'link']);
});

Route::get('{view}', ApplicationController::class)->where('view', '(.*)')->middleware('auth');
