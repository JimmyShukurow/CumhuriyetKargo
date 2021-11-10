<?php

use App\Http\Controllers\Backend\SystemSupport\SystemSupportController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {
    Route::group(['prefix' => 'SystemSupport', 'as' => 'systemSupport.'], function () {
        Route::get('NewTicket', [SystemSupportController::class, 'addTicket'])->name('NewTicket');
        Route::post('SendTicket', [SystemSupportController::class, 'createTicket'])->name('create');
        Route::get('MyTickets', [SystemSupportController::class, 'myTickets'])->name('myTickets');
        Route::post('ReplyTicket', [SystemSupportController::class, 'replyTicket'])->name('replyTicket');
        Route::get('TicketDetails/{TicketID}', [SystemSupportController::class, 'ticketDetails'])->name('TicketDetails');
    });
});
