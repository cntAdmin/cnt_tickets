<?php

use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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

Route::get('/testing', function (Request $req) {
    dd("NOTHING TO DO HERE");
});

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/virtualvoz', function () {
    return view('auth.virtualvoz.login');
});

Auth::routes();

Route::get('ticket/comment/{comment:_token}', 'TicketCommentController@getTicketThroughToken')->name('comment.token');
Route::get('ticket/ticket/{ticket:_token}', 'TicketController@getTicketThroughToken')->name('ticket.token');

Route::middleware(['auth'])->group(function () {

    Route::get('/my_custom_tests', 'TestController@my_custom_tests');

    Route::get('/dashboard', 'DashboardController@dashboard')->name('dashboard');
    Route::get('/profile/{user}', 'UserController@profile')->name('profile');
    Route::resource('ticket', TicketController::class)->only(['index', 'show', 'edit']);
    Route::resource('ticket.comment', TicketCommentController::class)->only(['create', 'edit']);
    Route::get('ticket-type/{ticketType}/ticket/crear', 'TicketController@create');
    Route::resource('attachment', AttachmentController::class)->only(['create', 'edit']);
    Route::resource('comment', CommentController::class)->only(['create', 'edit']);
    Route::resource('customer', CustomerController::class)->only(['index', 'show','create', 'edit']);
    Route::resource('customer.ticket', CustomerTicketController::class)->only(['index', 'show', 'create', 'edit']);
    Route::resource('department', DepartmentController::class)->only(['index', 'show', 'create', 'edit']);
    Route::resource('department-type', DepartmentTypeController::class)->only(['index', 'show', 'create', 'edit']);
    Route::resource('origin-type', OriginTypeController::class)->only(['index', 'show', 'create', 'edit']);
    Route::resource('ticket-type', TicketTypeController::class)->only(['index', 'show', 'create', 'edit']);
    Route::resource('ticket-status', TicketStatusController::class)->only(['index','show', 'create', 'edit']);
    Route::resource('user', UserController::class)->only(['index', 'show', 'create', 'edit']);

    Route::get('/import_siptize_customer', 'CustomerController@import_siptize_customer');  
    Route::resource('dashboard', 'DashboardController');
});
