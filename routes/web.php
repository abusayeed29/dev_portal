<?php

use App\Events\WebsocketDemoEvent;
use App\Http\Controllers\Admin\AdminProjectController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Sub\LeaveController as SubLeaveController;
use App\Http\Controllers\Sub\PrTaskDurationController;
use App\Http\Controllers\User\EmployeeController;
use App\Http\Controllers\Admin\EmployeeController as AdminEmployeeController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\VmsRequisitionController as AdminVmsRequisitionController;
use App\Http\Controllers\Admin\VmsSettingsController;
use App\Http\Controllers\Admin\VmsTeamController as AdminVmsTeamController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AstComponentController;
use App\Http\Controllers\AstMaintenanceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ChatsController;
use App\Http\Controllers\CustomLoginController;
use App\Http\Controllers\EmployeeController as ControllersEmployeeController;
use App\Http\Controllers\EventCalenderController;
use App\Http\Controllers\Head\DashboardController as HeadDashboardController;
use App\Http\Controllers\Head\EmployeeController as HeadEmployeeController;
use App\Http\Controllers\Head\SettingsController as HeadSettingsController;
use App\Http\Controllers\Head\TicketCommentsController as HeadTicketCommentsController;
use App\Http\Controllers\Head\TicketController as HeadTicketController;
use App\Http\Controllers\MtRoomBookController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\SlaDashboardController;
use App\Http\Controllers\SlaTaskCategoryController;
use App\Http\Controllers\SlaTaskController;
use App\Http\Controllers\SrmRequisitionController;
use App\Http\Controllers\Sub\DashboardController as SubDashboardController;
use App\Http\Controllers\Sub\EmployeeController as SubEmployeeController;
use App\Http\Controllers\Sub\SettingsController as SubSettingsController;
use App\Http\Controllers\Sub\TicketController as SubTicketController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketNotificationController;
use App\Http\Controllers\User\ClientsController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\ProjectController as UserProjectController;
use App\Http\Controllers\User\PrTaskController as UserPrTaskController;
use App\Http\Controllers\User\PrTaskDurationController as UserPrTaskDurationController;
use App\Http\Controllers\User\SettingsController;
use App\Http\Controllers\User\TicketCommentsController;
use App\Http\Controllers\User\TicketController as UserTicketController;
use App\Http\Controllers\VmsDriverController;
use App\Http\Controllers\VmsRequisitionController;
use App\Http\Controllers\VmsTeamController;
use App\Http\Controllers\VmsVehicleController;
use Illuminate\Support\Facades\Auth;
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


// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/socket', function () {
    broadcast(new WebsocketDemoEvent('some data'));
    return view('welcome');
});

Route::get('chats', [ChatsController::class, 'index']);
Route::get('/messages', [ChatsController::class, 'fetchMessages']);
Route::post('/messages', [ChatsController::class, 'sendMessages']);

//Route::get('/ticket/create', [TicketNotificationController::class, 'newTicket']);
Route::post('/ticket/notification', [TicketNotificationController::class, 'ticketNotification']);

Route::post('/ticket/notification/markAsRead', [TicketNotificationController::class, 'markAsRead'])->name('markAsread');
Route::get('/readTicket/notification/{ticket_id?}', [TicketNotificationController::class, 'readTicket'])->name('readTicket');

Route::post('/ticket/notification/allMarkAsRead', [TicketNotificationController::class, 'allMarkAsRead'])->name('allMarkAsRead');
Route::get('/notification/readAllTicket', [TicketNotificationController::class, 'readAllTicket'])->name('readAllTicket');

//end websocket


//Route::get('/', [PageController::class, 'index']);
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');

Route::get('/home2', [PageController::class, 'home2']);

Route::get('/attendence', [PageController::class, 'attendence']);
Route::get('/oracle/getleave', [PageController::class, 'getLeave']);
Route::get('/send-notification', [PageController::class, 'sendNotification']);
Route::get('/employee/search', [ControllersEmployeeController::class, 'findEmployeeById']);

// 3s routes
Route::get('/home', [PageController::class, 'index'])->name('home');
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{id}', [NewsController::class, 'show'])->name('news.show');
Route::get('/policy', [PageController::class, 'policies'])->name('policy.index');
Route::get('/about', [PageController::class, 'aboutUs'])->name('about');
Route::get('/functions', [PageController::class, 'nfunctions'])->name('functions');
Route::get('/forms', [PageController::class, 'forms'])->name('forms');
Route::get('/coming-soon', [PageController::class, 'comingSoon'])->name('coming');
Route::get('/womens', [PageController::class, 'womensNetwork'])->name('womens');

// end routes
Auth::routes(['verify' => true]);
Route::get('/login/{empid?}', [CustomLoginController::class, 'customLoginForm'])->name('login.custom');
//Auth::routes(['register' => false]);

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/get-task', [App\Http\Controllers\PageController::class, 'getTaskbyDepartment']);
Route::get('/get-user', [App\Http\Controllers\PageController::class, 'getUserByDepartment']);
Route::get('/get-department', [App\Http\Controllers\PageController::class, 'getDeparmentByCompany']);
Route::get('/get-department-designation', [App\Http\Controllers\PageController::class, 'getDeparmentDesignationByCompany']);

Route::get('/get-component-values', [App\Http\Controllers\PageController::class, 'getComponentValues']);

Route::get('/download', [App\Http\Controllers\PageController::class, 'blobDownload']);
Route::get('/getemployee', [App\Http\Controllers\PageController::class, 'getEmployes']);

Route::get('/picnic', [ArchiveController::class, 'index'])->name('archive.picnic');
Route::get('/server', [PageController::class, 'serverStatus'])->name('server');
Route::get('/getserver', [PageController::class, 'getServerStatus'])->name('server.status');

Route::get('/getsession', [PageController::class, 'getSession'])->name('getsession');
Route::get('/killsession', [PageController::class, 'killSession'])->name('killsession');

Route::get('/get-workplace', [App\Http\Controllers\EmployeeController::class, 'getworkPlaceByCompany']);

// ticket common
Route::get('/ticket-types', [App\Http\Controllers\TicketController::class, 'ticketTypeByDepartment']);
Route::get('/tkt-team-user', [App\Http\Controllers\TicketController::class, 'tktTeamUser']);

Route::get('/tickets/company-reports', [App\Http\Controllers\TicketController::class, 'companyTickets']);
//Route::get('/tickets/company/{id}', [App\Http\Controllers\TicketController::class, 'companyTickets'])->name('company.tickets');

Route::get('/department/ticket',[TicketController::class, 'departmentTicket'])->name('department.ticket')->middleware('auth');

Route::group(['as'=>'roster.','middleware'=>['auth']],function () {
    Route::get('/event/roster', [EventCalenderController::class, 'index'])->name('index');
    Route::get('/get-event', [EventCalenderController::class, 'getRoster']);
    Route::post('/post-event', [EventCalenderController::class, 'store'])->name('store');
    Route::post('/update-event', [EventCalenderController::class, 'update'])->name('update');
    Route::get('/roster/list', [EventCalenderController::class, 'allRoster'])->name('list');
    Route::delete('/delete-event/{id}', [EventCalenderController::class, 'destroy'])->name('destroy');

});

Route::group(['as' => 'vms.', 'prefix' => 'vms','middleware'=>['auth']], function () {

    Route::get('requisition', [VmsRequisitionController::class, 'index'])->name('manage');
    Route::get('requisition/deatils/{id}', [VmsRequisitionController::class, 'show'])->name('show');
    Route::post('requisition', [VmsRequisitionController::class, 'store'])->name('requisition.store');
    //Route::get('requisition/department', [VmsRequisitionController::class, 'approveByDepartment'])->name('new.approve.department');
    //Route::get('requisition/approve', [VmsRequisitionController::class, 'newApprove'])->name('new.approve');
    Route::get('requisition/release/{id}/edit', [VmsRequisitionController::class, 'openReleaseModal'])->name('release');
    Route::post('requisition/release', [VmsRequisitionController::class, 'requisitionRelease']);
    Route::get('requisition/stage/aprrove/{id}/edit', [VmsRequisitionController::class, 'edit'])->name('edit');

    Route::get('requisition/stage/{stage}', [VmsRequisitionController::class, 'requisitionByStage'])->name('requisition.stage');

    Route::name('driver.')->group(function () {
        Route::post('/driver', [VmsDriverController::class, 'store'])->name('store');
        Route::get('drivers', [VmsDriverController::class, 'index'])->name('manage');
        Route::get('drivers/{id}/edit', [VmsDriverController::class, 'edit']);
    });
    Route::name('vehicle.')->group(function () {
        Route::get('vehicles', [VmsVehicleController::class, 'index'])->name('manage');
        Route::post('/vehicle', [VmsVehicleController::class, 'store'])->name('store');
        Route::get('/vehicle/{id}', [VmsVehicleController::class, 'show'])->name('show');
        Route::get('vehicle/{id}/edit', [VmsVehicleController::class, 'edit']);
    });

    Route::name('team.')->group(function () {
        Route::get('teams', [VmsTeamController::class, 'index'])->name('index');
    });

    
});

// Requistion common
Route::post('requisition/approve', [VmsRequisitionController::class, 'approveStore'])->name('approve')->middleware('auth');
Route::get('/driver/search', [VmsDriverController::class, 'findDriver'])->middleware('auth');

Route::group(['as' => 'book.', 'prefix' => 'book','middleware'=>['auth']], function () {
    Route::get('/meeting-room', [MtRoomBookController::class, 'index'])->name('index');
    Route::post('/meeting-room', [MtRoomBookController::class, 'store'])->name('store');
    Route::get('/meeting-room/search', [MtRoomBookController::class, 'meetingRoomSearch']);
    Route::get('/get-room-book', [MtRoomBookController::class, 'getBooking']);

    Route::get('/meeting-room/list', [MtRoomBookController::class, 'allBooked'])->name('list');

    Route::delete('/delete-booking/{id}', [MtRoomBookController::class, 'destroy'])->name('destroy');
});

Route::group(['as' => 'asset.', 'prefix' => 'asset','middleware'=>['auth']], function () {

    Route::get('/dashboard', [AssetController::class, 'astDashboard'])->name('dashboard');
    Route::get('/manage', [AssetController::class, 'index'])->name('manage');
    Route::post('/manage', [AssetController::class, 'store'])->name('manage.store');
    Route::get('/manage/{id}/detail', [AssetController::class, 'show'])->name('show');

    Route::get('/manage/data', [AssetController::class, 'getAssetData'])->name('manage.jsondata');

    Route::post('/built-component/store', [AssetController::class, 'astComponentStore'])->name('component');
    Route::post('/new-component/store', [AssetController::class, 'astNewComponent'])->name('newcomponent');
    Route::get('/built-component/search', [AssetController::class, 'assetBuiltInComponent']);

    Route::name('component.')->group(function () {
        Route::get('component/manage', [AstComponentController::class, 'index'])->name('manage');
        Route::post('component/manage', [AstComponentController::class, 'store'])->name('store');
        Route::get('/component/{id}/show', [AstComponentController::class, 'show'])->name('show');
        Route::get('/detail/component/{id}/edit', [AstComponentController::class, 'edit'])->name('edit');
        Route::get('/component/search', [AstComponentController::class, 'componentSearch']);
    });

    Route::name('maintenance.')->group(function () {
        Route::get('maintenance/manage', [AstMaintenanceController::class, 'index'])->name('manage');
    });

});



Route::group(['as'=>'admin.','prefix'=>'admin','namespace'=>'Admin','middleware'=>['auth','admin']], function (){
    Route::get('dashboard',[DashboardController::class, 'index'])->name('dashboard');

    Route::name('projects.')->group(function() {
        Route::get('/projects', [AdminProjectController::class, 'index'])->name('index');
        Route::get('/projects/create', [AdminProjectController::class, 'create'])->name('create');
        Route::post('/projects', [AdminProjectController::class, 'store'])->name('store');
        Route::get('/projects/{id}', [AdminProjectController::class, 'show'])->name('show');
        Route::get('/projects/{id}/edit', [AdminProjectController::class, 'edit'])->name('edit');
        Route::delete('/projects/{id}', [AdminProjectController::class, 'destroy'])->name('destroy');
    });

    Route::name('tasks.')->group(function() {
        Route::get('/project/task', [PrTaskController::class, 'index'])->name('index');
        Route::get('/project/task/create', [PrTaskController::class, 'create'])->name('create');
        Route::post('/project/task', [PrTaskController::class, 'store'])->name('store');

        Route::name('duration.')->group(function() {
            Route::post('/project/task/duration', [PrTaskDurationController::class, 'store'])->name('store');
        });

    });

    Route::name('leave.')->group(function() {
        Route::get('/leave', [SubLeaveController::class, 'index'])->name('index');
        Route::get('/leave/apply', [SubLeaveController::class, 'create'])->name('create');
    });

    Route::group(['as'=>'user.','prefix'=>'user'], function () {
        Route::get('/employee', [AdminUserController::class, 'index'])->name('index');
        Route::get('/employee/create', [AdminUserController::class, 'create'])->name('create');
        Route::post('/employee/store', [AdminUserController::class, 'store'])->name('store');
        Route::get('/employee/profile/{id}', [AdminUserController::class, 'show'])->name('show');
        Route::put('/employee/update', [AdminUserController::class, 'update'])->name('update');

        //Route::get('/employee/{id}', [EmployeeController::class, 'show'])->name('show');

        Route::get('/employee/data', [AdminUserController::class, 'jsonUserData'])->name('jsondata');
        Route::post('/employee/permission', [AdminUserController::class, 'permissionUpdate'])->name('permission');

        Route::post('/employee/hierarchy', [AdminUserController::class, 'hierarchyStore'])->name('hierarchy');

        Route::get('/add-update', [App\Http\Controllers\PageController::class, 'getEmployee'])->name('addUpdate');

        Route::get('/all-employees', [AdminEmployeeController::class, 'index'])->name('all.employees');
        Route::get('/all-employees/data', [AdminEmployeeController::class, 'jsonEmployeeData'])->name('jsonEmployeeData');

    });

    Route::group(['as'=>'vms.','prefix'=>'vms'], function () {

        Route::name('settings.')->group(function() {
            Route::get('/settings', [VmsSettingsController::class, 'index'])->name('index');
            Route::post('/store', [VmsSettingsController::class, 'store'])->name('store');
            Route::get('/settings/{id}/edit', [VmsSettingsController::class, 'addTeamUser'])->name('team');
            Route::post('/settings/team-user/store', [VmsSettingsController::class, 'storeTeamUser'])->name('team.user');
        });

        Route::get('requisition', [AdminVmsRequisitionController::class, 'index'])->name('manage');
        Route::get('requisition/deatils/{id}', [VmsRequisitionController::class, 'show'])->name('show');
        Route::post('requisition', [VmsRequisitionController::class, 'store'])->name('requisition.store');
        Route::get('requisition/aprrove', [VmsRequisitionController::class, 'newApprove'])->name('new.approve');
        Route::get('requisition/aprrove/{id}/edit', [VmsRequisitionController::class, 'edit'])->name('edit');

        Route::name('driver.')->group(function () {
            Route::get('drivers', [VmsDriverController::class, 'index'])->name('manage');
            Route::get('drivers/{id}/edit', [VmsDriverController::class, 'edit']);
        });
        Route::name('vehicle.')->group(function () {
            Route::get('vehicles', [VmsVehicleController::class, 'index'])->name('manage');
            Route::get('vehicle/{id}/edit', [VmsVehicleController::class, 'edit']);
        });

        Route::name('team.')->group(function () {
            Route::get('teams', [AdminVmsTeamController::class, 'index'])->name('index');
        });


    });

});

Route::group(['as'=>'head.','prefix'=>'head','namespace'=>'Head','middleware'=>['auth'=>'verified','head']], function (){

    Route::get('dashboard',[HeadDashboardController::class, 'index'])->name('dashboard');
    
    Route::name('settings.')->group(function () {
        Route::get('profile', [HeadSettingsController::class, 'index'])->name('profile');
        Route::put('/profile/update', [HeadSettingsController::class, 'update'])->name('update');

        Route::get('profile/change-password',[HeadSettingsController::class, 'changePasswordGet'])->name('changePasswordGet');
        Route::post('profile/change-password',[HeadSettingsController::class, 'changePassword'])->name('changePasswordPost');
    });

    Route::name('sla.')->group(function () {
        Route::get('sla-dashboard',[SlaDashboardController::class, 'index'])->name('dashboard');

        Route::get('sla/categories',[SlaTaskCategoryController::class, 'index'])->name('categories');

        Route::post('sla/department/task',[SlaTaskCategoryController::class, 'departmentTask'])->name('department.task');

        Route::get('sla/tasks',[SlaTaskController::class, 'index'])->name('tasks');
        Route::post('sla/tasks',[SlaTaskController::class, 'store'])->name('task.store');
        Route::get('sla/task/{id}',[SlaTaskController::class, 'show'])->name('tasks.show');
        Route::post('/sla/tasks/cate/modal', [SlaTaskController::class, 'slaTaskCateModal'])->name('task.cate.modal');
        Route::post('/sla/tasks/cate/update', [SlaTaskController::class, 'slaTaskCateUpdate'])->name('task.cate.update');
        

    });
    
    Route::name('ticket.')->group(function () {
        Route::get('/ticket', [HeadTicketController::class, 'index'])->name('index');
        //Route::post('/ticket', [HeadTicketController::class, 'store'])->name('store');
        Route::post('/ticket', [TicketController::class, 'store'])->name('store');
        Route::get('/ticket/{id}', [HeadTicketController::class, 'show'])->name('show');
        Route::get('/ticket/{id}/edit', [HeadTicketController::class, 'edit'])->name('edit');
        Route::get('/ticket/status/{type}', [HeadTicketController::class, 'showByStatus'])->name('status');

        Route::get('/support/ticket', [HeadTicketController::class, 'support'])->name('support');
        //Route::get('/support/ticket/{id}', [HeadTicketController::class, 'supportShow'])->name('support.show');

        Route::get('/manage/ticket', [HeadTicketController::class, 'headManageTicket'])->name('departmenthead.manage');
        Route::get('/manage/data', [HeadTicketController::class, 'headManageTicketData'])->name('departmenthead.jsondata');

        Route::post('/ticket/assign/data', [HeadTicketController::class, 'assignUserModal'])->name('assign.user');
        Route::post('/ticket/assign/update', [HeadTicketController::class, 'update'])->name('assign.update');
        
        Route::name('comments.')->group(function () {
            Route::post('/comments', [HeadTicketCommentsController::class, 'store'])->name('store');
        });

        Route::get('/report/show', [HeadTicketController::class, 'report'])->name('report');

    });

    Route::name('employee.')->group(function () {
        Route::get('/employee/data', [HeadEmployeeController::class, 'jsonUsers'])->name('jsondata');
        Route::get('/employee', [HeadEmployeeController::class, 'index'])->name('index');
        Route::get('/employee/{id}', [HeadEmployeeController::class, 'show'])->name('show');
        Route::post('/employee/hierarchy', [HeadEmployeeController::class, 'hierarchyStore'])->name('hierarchy');
    });

    Route::name('vms.')->group(function () {
        Route::get('requisition', [VmsRequisitionController::class, 'index'])->name('manage');
        Route::get('requisition/deatils/{id}', [VmsRequisitionController::class, 'show'])->name('show');
        Route::post('requisition', [VmsRequisitionController::class, 'store'])->name('requisition.store');
        Route::get('requisition/approve', [VmsRequisitionController::class, 'newApprove'])->name('new.approve');
        Route::get('requisition/aprrove/{id}/edit', [VmsRequisitionController::class, 'edit'])->name('edit');

        Route::name('driver.')->group(function () {
            Route::get('drivers', [VmsDriverController::class, 'index'])->name('manage');
        });
        Route::name('vehicle.')->group(function () {
            Route::get('vehicles', [VmsVehicleController::class, 'index'])->name('manage');
        });

    });
    
    
});

Route::group(['as'=>'sub.','prefix'=>'sub','namespace'=>'sub','middleware'=>['auth'=>'verified','sub']], function (){
    Route::get('dashboard',[SubDashboardController::class, 'index'])->name('dashboard');

    Route::name('settings.')->group(function () {
        Route::get('profile', [SubSettingsController::class, 'index'])->name('profile');
        Route::put('/profile/update', [SubSettingsController::class, 'update'])->name('update');

        Route::get('profile/change-password',[SubSettingsController::class, 'changePasswordGet'])->name('changePasswordGet');
        Route::post('profile/change-password',[SubSettingsController::class, 'changePassword'])->name('changePasswordPost');
        Route::post('profile/phone',[SubSettingsController::class, 'phoneSave'])->name('profile.phone');
    });

    Route::name('employee.')->group(function () {
        Route::get('/employee/data', [SubEmployeeController::class, 'jsonUsers'])->name('jsondata');
        Route::get('/employee', [SubEmployeeController::class, 'index'])->name('index');
        Route::get('/employee/{id}/details', [SubEmployeeController::class, 'show'])->name('show');
        Route::post('/employee/store', [SubEmployeeController::class, 'store'])->name('store');
        Route::post('/employee/hierarchy', [SubEmployeeController::class, 'hierarchyStore'])->name('hierarchy');
        Route::put('employee/update', [SubEmployeeController::class, 'update'])->name('update');
    });

    Route::name('ticket.')->group(function () {
        Route::get('/ticket', [SubTicketController::class, 'index'])->name('index');
        //Route::post('/ticket', [SubTicketController::class, 'store'])->name('store');
        Route::post('/ticket', [TicketController::class, 'store'])->name('store');
        Route::get('/ticket/{id}', [SubTicketController::class, 'show'])->name('show');

        Route::post('/ticket/remove', [SubTicketController::class, 'destroy'] )->name('destroy');

        Route::post('/ticket/logs', [SubTicketController::class, 'storeDailyLogs'])->name('dailyLogs');

        Route::get('/support', [SubTicketController::class, 'support'])->name('support');

        Route::get('/ticket/status/{type}', [SubTicketController::class, 'showByStatus'])->name('status');

        Route::get('/support/data', [SubTicketController::class, 'supportTicketData'])->name('support.jsondata');
        //Route::get('/support/ticket/{id}', [SubTicketController::class, 'supportShow'])->name('support.show');

        Route::get('/manage/data', [SubTicketController::class, 'superVisorManageTicketData'])->name('supervisor.jsondata');
        Route::get('/manage/ticket', [SubTicketController::class, 'manageTicket'])->name('support.manage');
        Route::post('/ticket/assign/data', [SubTicketController::class, 'assignUserModal'])->name('assign.user');
        Route::post('/ticket/assign/update', [SubTicketController::class, 'updateBySuperVisor'])->name('assign.update');

        Route::post('/ticket/update/status', [SubTicketController::class, 'updateStatusByEngineer'])->name('update.status');
        Route::post('/ticket/update/status/cancel', [SubTicketController::class, 'updateCancelStatusByEngineer'])->name('update.status.cancel');
        Route::post('/ticket/feedback', [SubTicketController::class, 'feedback'])->name('feedback');

        Route::name('comments.')->group(function () {
            Route::post('/comments', [TicketCommentsController::class, 'store'])->name('store');
        });

        Route::get('/report/show', [SubTicketController::class, 'report'])->name('report');
        
    });

    /*Route::name('vms.')->group(function () {
        Route::get('requisition', [VmsRequisitionController::class, 'index'])->name('manage');
        Route::get('requisition/deatils/{id}', [VmsRequisitionController::class, 'show'])->name('show');
        Route::post('requisition', [VmsRequisitionController::class, 'store'])->name('requisition.store');
        Route::get('requisition/approve', [VmsRequisitionController::class, 'newApprove'])->name('new.approve');
        Route::get('requisition/aprrove/{id}/edit', [VmsRequisitionController::class, 'edit'])->name('edit');

        Route::name('driver.')->group(function () {
            Route::get('drivers', [VmsDriverController::class, 'index'])->name('manage');
        });
        Route::name('vehicle.')->group(function () {
            Route::get('vehicles', [VmsVehicleController::class, 'index'])->name('manage');
        });

    }); */


});

Route::group(['as'=>'user.','prefix'=>'user','namespace'=>'User','middleware'=>['auth'=>'verified','user']], function (){

    Route::get('dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    Route::name('settings.')->group(function () {
        Route::get('profile', [SettingsController::class, 'index'])->name('profile');
        Route::put('/profile/update', [SettingsController::class, 'update'])->name('update');

        Route::get('profile/change-password',[SettingsController::class, 'changePasswordGet'])->name('changePasswordGet');
        Route::post('profile/change-password',[SettingsController::class, 'changePassword'])->name('changePasswordPost');

        Route::post('profile/phone',[SettingsController::class, 'phoneSave'])->name('profile.phone');
    });

    Route::name('projects.')->group(function () {
        Route::get('/projects', [UserProjectController::class, 'index'])->name('index');
        Route::get('/projects/create', [UserProjectController::class, 'create'])->name('create');
        Route::post('/projects', [UserProjectController::class, 'store'])->name('store');
        Route::get('/projects/{id}', [UserProjectController::class, 'show'])->name('show');
        Route::get('/projects/{id}/edit', [UserProjectController::class, 'edit'])->name('edit');
        Route::delete('/projects/{id}', [UserProjectController::class, 'destroy'])->name('destroy');

        Route::post('/projects/task/status', [UserProjectController::class, 'changeStatus'])->name('task.change');
    });
    Route::name('clients.')->group(function () {
        Route::get('/clients', [ClientsController::class, 'index'])->name('index');
    });

    Route::name('tasks.')->group(function () {
        Route::get('/project/task', [UserPrTaskController::class, 'index'])->name('index');
        Route::get('/project/task/create', [UserPrTaskController::class, 'create'])->name('create');
        Route::post('/project/task', [UserPrTaskController::class, 'store'])->name('store');

        Route::name('duration.')->group(function () {
            Route::post('/project/task/duration', [UserPrTaskDurationController::class, 'store'])->name('store');
        });
        
    });

    Route::name('employee.')->group(function () {
        Route::get('/employee/data', [EmployeeController::class, 'jsonUsers'])->name('jsondata');
        Route::get('/employee', [EmployeeController::class, 'index'])->name('index');
        Route::get('/employee/{id}', [EmployeeController::class, 'show'])->name('show');

        Route::post('/employee/store', [EmployeeController::class, 'store'])->name('store');

        Route::post('/employee/hierarchy', [EmployeeController::class, 'hierarchyStore'])->name('hierarchy');
    });
    
    Route::name('ticket.')->group(function () {
        Route::get('/ticket', [UserTicketController::class, 'index'])->name('index');
        Route::post('/ticket', [TicketController::class, 'store'])->name('store');
        Route::get('/ticket/{id}', [UserTicketController::class, 'show'])->name('show');

        Route::post('/ticket/logs', [UserTicketController::class, 'storeDailyLogs'])->name('dailyLogs');

        Route::get('/support', [UserTicketController::class, 'support'])->name('support');
        Route::get('/support/data', [UserTicketController::class, 'supportTicketData'])->name('support.jsondata');
        
        Route::post('/ticket/update/status', [UserTicketController::class, 'updateStatusByEngineer'])->name('update.status');
        Route::post('/ticket/feedback', [UserTicketController::class, 'feedback'])->name('feedback');

        Route::name('comments.')->group(function () {
            Route::post('/comments', [TicketCommentsController::class, 'store'])->name('store');
        });

        // notification socket
        
    });

    /*Route::group(['as'=>'vms.','prefix'=>'vms'], function () {
        Route::get('requisition', [VmsRequisitionController::class, 'index'])->name('manage');
        Route::get('requisition/deatils/{id}', [VmsRequisitionController::class, 'show'])->name('show');
        Route::post('requisition', [VmsRequisitionController::class, 'store'])->name('requisition.store');
        Route::get('requisition/aprrove', [VmsRequisitionController::class, 'newApprove'])->name('new.approve');
        Route::get('requisition/aprrove/{id}/edit', [VmsRequisitionController::class, 'edit'])->name('edit');

        Route::name('driver.')->group(function () {
            Route::get('drivers', [VmsDriverController::class, 'index'])->name('manage');
            Route::get('drivers/{id}/edit', [VmsDriverController::class, 'edit']);
        });
        Route::name('vehicle.')->group(function () {
            Route::get('vehicles', [VmsVehicleController::class, 'index'])->name('manage');
            Route::get('vehicle/{id}/edit', [VmsVehicleController::class, 'edit']);
        });
        
    }); */

    Route::group(['as'=>'srm.','prefix'=>'srm'],function(){
        Route::get('requisition', [SrmRequisitionController::class, 'index'])->name('manage');
    });

});
   
