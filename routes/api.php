<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/ticket/{ticket}/comment', 'TicketCommentController@store')->name('ticket.comment.store');
Route::post('/annonymous_ticket', 'TicketController@annonymous_ticket')->name('annonymous_ticket');
Route::get('/get_all_departments', 'DepartmentController@get_all_departments')->name('get_all_departments');
Route::get('/department/{department}/department_types', 'DepartmentTypeController@get_department_department_types')
->name('get_department_department_types');
Route::get('/get_all_priorities', 'PriorityController@get_all_priorities')->name('get_all_priorities');

Route::middleware(['auth:web'])->group(function () {
    // * GETTERS 
    Route::get('/get_ticket_status_count', 'TicketController@count')->name('count');
    Route::get('/get_all_users', 'UserController@get_all_users')->name('get_all_users');
    Route::get('/get_all_roles', 'RoleController@get_all_roles')->name('get_all_roles');
    Route::get('/get_all_customers', 'CustomerController@get_all_customers')->name('get_all_customers');
    Route::get('/get_all_agents', 'UserController@get_all_agents')->name('get_all_agents');
    Route::get('/get_all_origins', 'OriginTypeController@get_all_origins')->name('get_all_origins');
    Route::get('/get_all_ticket_types', 'TicketTypeController@get_all_ticket_types')->name('get_all_ticket_types');
    Route::get('/get_all_ticket_statuses', 'TicketStatusController@get_all_ticket_statuses')->name('get_all_ticket_statuses');
    Route::get('/get_all_warranties', 'WarrantyController@get_all_warranties')->name('get_all_warranties');
    Route::get('/get_all_invoiceable_types', 'InvoiceableTypeController@get_all_invoiceable_types')->name('get_all_invoiceable_types');
    Route::get('/get_all_users_asignables', 'UserController@get_all_users_asignables')->name('get_all_users_asignables');
    Route::get('/get_all_department_types', 'DepartmentTypeController@get_all_department_types')->name('get_all_department_types');
    
    Route::get('/get_user', 'UserController@get_user')->name('get_user');
    Route::put('/ticket/{ticket}/ticket-status/{ticketStatus}', 'TicketStatusController@change_status')->name('change_status');

    
    Route::get('/customer/{customer}/user', 'UserController@get_customer_users')->name('get_customer_users');
    Route::get('/attachment/{attachment}/download', 'AttachmentController@download')->name('download_attachment');
    Route::get('/ticket/{ticket}/attachment', 'TicketController@get_ticket_attachments')->name('get_ticket_attachments');

    // RESOURCES
    Route::apiResource('ticket', TicketController::class)->except('show');
    Route::apiResource('ticket.comment', TicketCommentController::class)->except('store');
    Route::apiResources([
        'attachment' => AttachmentController::class,
        'comment' => CommentController::class,
        'customer' => CustomerController::class,
        'customer.ticket' => CustomerTicketController::class,
        'department' => DepartmentController::class,
        'department-type' => DepartmentTypeController::class,
        'origin-type' => OriginTypeController::class,
        'ticket-type' => TicketTypeController::class,
        'ticket-status' => TicketStatusController::class,
        'ticket-timeslot' => TicketTimeslotController::class,
        'user' => UserController::class
        ]);
});