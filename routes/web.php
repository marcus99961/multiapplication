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
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\ReceiveController;
use App\Http\Controllers\Admin\IssueController;
use App\Http\Controllers\Admin\MultiselectController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StockController;
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


   

    Route::get('/api/settings', [SettingController::class, 'index']);
    Route::post('/api/settings', [SettingController::class, 'update']);
    Route::post('/api/truncate', [SettingController::class, 'truncate']);

    Route::get('/api/profile', [ProfileController::class, 'index']);
    Route::put('/api/profile', [ProfileController::class, 'update']);
    Route::post('/api/upload-profile-image', [ProfileController::class, 'uploadImage']);
    Route::post('/api/change-user-password', [ProfileController::class, 'changePassword']);

    // Route::get('/api/getItems', [LostController::class, 'index']);
    // Route::get('/api/getClaimedItems', [LostController::class, 'ClaimedIndex']);
    // Route::get('/api/getlostcounts', [LostController::class, 'lostcount']);
    // Route::post('/api/storeItem', [LostController::class, 'store']);
    // Route::post('/api/updateItem', [LostController::class, 'update']);
    // Route::post('/api/claimItem', [LostController::class, 'claim']);
    // Route::post('/api/claimItems', [LostController::class, 'claimItems']);
    // Route::post('/api/bulkDelete', [LostController::class, 'bulkDelete']);
    // Route::post('/api/reportItems', [LostController::class, 'reportItems']);
    // Route::delete('/api/deleteItem/{item}', [LostController::class, 'destroy']);

    Route::get('/api/getTvs', [TvController::class, 'index']);
    Route::post('/api/storeTv', [TvController::class, 'store']);
    Route::delete('/api/deleteTv/{item}', [TvController::class, 'destroy']);
    Route::post('/api/updateTv', [TvController::class, 'update']);


    // Route::get('/api/getRooms', [RoomController::class, 'index']);
    // Route::get('/api/selectedRooms', [RoomController::class, 'selectedrooms']);
    // Route::get('/api/getRoom2', [RoomController::class, 'index2']);
    // Route::post('/api/storeRoom', [RoomController::class, 'store']);
    // Route::post('/api/storeRoom2', [RoomController::class, 'store2']);
    // Route::post('/api/updateRoom/{id}', [RoomController::class, 'update']);


    Route::get('/api/getDepartments', [DepartmentController::class, 'index']);
    Route::get('/api/getDepartment2', [DepartmentController::class, 'index2']);
    Route::post('/api/storeDepartment', [DepartmentController::class, 'store']);

    // Route::get('/api/getMembers', [MemberController::class, 'index']);
    // Route::get('/api/getInactiveMembers', [MemberController::class, 'inactive']);
    // Route::get('/api/getcomplaintmembers', [MemberController::class, 'complaintmember']);
    // Route::get('/api/getcomplaintmember', [MemberController::class, 'complaintmember2']);
    // Route::get('/api/getMember2/{id}', [MemberController::class, 'index2']);
    // Route::post('/api/storeMember', [MemberController::class, 'store']);
    // Route::post('/api/storeMemberdepartment', [MemberController::class, 'storebydepartment']);
    // Route::post('/api/updateMember/{id}', [MemberController::class, 'update']);

    // Route::post('/api/storeMessage', [MessageController::class, 'store']);
    // Route::get('/api/getComplaintmessages/{id}', [MessageController::class, 'show']);
    // Route::get('/api/getmessages', [MessageController::class, 'index']);
    // Route::get('/api/getcounts', [MessageController::class, 'count']);

    // Route::get('/api/getComplaints', [ComplaintController::class, 'index']);
    // Route::get('/api/findComplaints', [ComplaintController::class, 'find']);
    // Route::get('/api/sendDailyreport', [ComplaintController::class, 'dailyReport']);
    // Route::get('/api/getComplaints/user', [ComplaintController::class, 'index_user']);
    // Route::get('/api/getComplaints/service', [ComplaintController::class, 'index_service']);
    // Route::get('/api/getcomplaints', [ComplaintController::class, 'index2']);
    // Route::get('/api/getComplaints/{id}', [ComplaintController::class, 'show']);
    // Route::get('/api/getMessagecount', [ComplaintController::class, 'count']);
    // Route::get('/api/getcomplaintcounts', [ComplaintController::class, 'countcomplaint']);
    // Route::get('/api/getAttachments', [ComplaintController::class, 'attachment']);
    // Route::post('/api/storePhoto', [ComplaintController::class, 'storePhoto']);
    // Route::post('/api/storeComplaint', [ComplaintController::class, 'store']);
    // Route::post('/api/updateComplaint', [ComplaintController::class, 'update']);
    // Route::post('/api/storeStatus', [ComplaintController::class, 'updateStatus']);
    // Route::post('/api/reportComplaints', [ComplaintController::class, 'reportComplaints']);
    // Route::post('/api/reportComplaintbyRoom', [ComplaintController::class, 'reportComplaintbyRoom']);
    // Route::delete('/api/complaints/{id}', [ComplaintController::class, 'destroy']);
    // Route::delete('/api/closecomplaints/{id}', [ComplaintController::class, 'close']);
    // Route::delete('/api/attachment/{id}', [ComplaintController::class, 'deletephoto']);

    Route::get('/api/getCategories', [CategoryController::class, 'index']);    
    Route::get('/api/getDepartmentcategories/{id}', [CategoryController::class, 'show']);

    Route::get('/api/getCategory2', [CategoryController::class, 'index2']);
    Route::post('/api/storeCategory', [CategoryController::class, 'store']);
    Route::post('/api/updateCategory/{id}', [CategoryController::class, 'update']);
    Route::delete('/api/deleteCategory/{category}', [CategoryController::class, 'destroy']);
    Route::delete('/api/departmentcategory/{id}', [CategoryController::class, 'unlink']);
    Route::post('/api/departmentCategory/{id}', [CategoryController::class, 'link']);

    Route::get('/api/getUnits', [UnitController::class, 'index']); 
    Route::post('/api/storeUnit', [UnitController::class, 'store']);
    Route::post('/api/updateUnit/{id}', [UnitController::class, 'update']);
    Route::delete('/api/deleteUnit/{unit}', [UnitController::class, 'destroy']);

    Route::get('/api/getItems', [ItemController::class, 'index']);
    Route::get('/api/getItem', [ItemController::class, 'index2']);
    Route::post('/api/storeItem', [ItemController::class, 'store']);
    Route::post('/api/updateItem/{id}', [ItemController::class, 'update']);
    Route::delete('/api/deleteItem/{item}', [ItemController::class, 'destroy']);

    Route::get('/api/getLocations', [LocationController::class, 'index']);
    Route::get('/api/getLocation', [LocationController::class, 'index2']);
    Route::post('/api/storeLocation', [LocationController::class, 'store']);
    Route::post('/api/updateLocation/{id}', [LocationController::class, 'update']);
    Route::delete('/api/deleteLocation/{location}', [LocationController::class, 'destroy']);

    Route::get('/api/getSuppliers', [SupplierController::class, 'index']);
    Route::post('/api/storeSupplier', [SupplierController::class, 'store']);
    Route::post('/api/updateSupplier/{id}', [SupplierController::class, 'update']);
    Route::delete('/api/deleteSupplier/{supplier}', [SupplierController::class, 'destroy']);

    Route::get('/api/getInvoices', [InvoiceController::class, 'index']);  
    Route::get('/api/getInvoice_no', [InvoiceController::class, 'edit']);
    Route::post('/api/storeInvoice', [InvoiceController::class, 'store']);
    Route::post('/api/updateInvoice/{id}', [InvoiceController::class, 'update']);
    Route::delete('/api/deleteInvoice/{invoice}', [InvoiceController::class, 'destroy']);
   

    Route::get('/api/getSelectedsupplier', [MultiselectController::class, 'supplier']);
    Route::get('/api/getSelecteddepartment', [MultiselectController::class, 'departments']);
    Route::get('/api/getSelectedlocation', [MultiselectController::class, 'location']);
    Route::get('/api/getSelectedunit', [MultiselectController::class, 'unit']);
    Route::get('/api/getSelectedcategory', [MultiselectController::class, 'category']);
    Route::get('/api/getSelecteditem', [MultiselectController::class, 'item']);
    Route::get('/api/getSelecteditems', [MultiselectController::class, 'items']);
    Route::get('/api/getPosted', [MultiselectController::class, 'invoice']);
    Route::get('/api/getPostedissue', [MultiselectController::class, 'issue']);
    Route::get('/api/getSourcelocations', [MultiselectController::class, 'sourcelocations']);
    Route::get('/api/getSourceitem', [MultiselectController::class, 'sourceitems']);
    Route::get('/api/getSourceinvoices', [MultiselectController::class, 'sourceinvoices']);
    Route::get('/api/getSourceitems', [MultiselectController::class, 'sourceitem']);
    Route::get('/api/getSourceitemqty', [MultiselectController::class, 'sourceitemqty']);
    Route::get('/api/getSourceitemqtys', [MultiselectController::class, 'sourceitemqtys']);


   // Route::get('/api/getDestinationlocations', [MultiselectController::class, 'destinations']);

    Route::get('/api/getReceives', [ReceiveController::class, 'index']);
    Route::get('/api/getTotals', [ReceiveController::class, 'total']);
    Route::post('/api/storeReceive/{id}', [ReceiveController::class, 'store']);
    Route::post('/api/updateReceive/{id}', [ReceiveController::class, 'update']);
    Route::delete('/api/deleteReceive/{receive}', [ReceiveController::class, 'destroy']);
    Route::post('/api/posting/{id}',[ReceiveController::class, 'post']);
    Route::post('/api/undoPosting', [ReceiveController::class, 'undoPosting']);

    Route::get('/api/stockStore', [StockController::class, 'stockstore']);
    Route::get('/api/getLocationitems', [StockController::class, 'locationitems']);
    Route::get('/api/getStockbylocations', [StockController::class, 'stockbylocations']);     
    Route::post('/api/transferItem', [StockController::class, 'transferitem']);

    Route::get('/api/getIssues', [IssueController::class, 'index']);  
    Route::get('/api/getIssueitems', [IssueController::class, 'indexitem']);  
    Route::get('/api/getIssue_no', [IssueController::class, 'edit']);
    Route::post('/api/storeIssue', [IssueController::class, 'store']);
    Route::post('/api/issueposting/{id}',[IssueController::class, 'post']);
    Route::post('/api/storeIssueitems/{id}', [IssueController::class, 'storeitems']);
    Route::post('/api/updateIssue/{id}', [IssueController::class, 'update']);
    Route::delete('/api/deleteIssue/{issue}', [IssueController::class, 'destroy']);
    Route::delete('/api/deleteIssueitem/{issue}', [IssueController::class, 'destroyitem']);
    Route::post('/api/undoissuePosting', [IssueController::class, 'undoPosting']);

    Route::post('/api/getStockbalance', [ReportController::class, 'stockbalance']);
    Route::post('/api/getStocksummary', [ReportController::class, 'stocksummary']);   
    Route::post('/api/getLedger', [ReportController::class, 'ledger']);   
    Route::post('/api/getTakingform', [ReportController::class, 'takingform']);  

});

Route::get('{view}', ApplicationController::class)->where('view', '(.*)')->middleware('auth');
