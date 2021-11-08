<?php

use App\Http\Controllers\Backend\SystemSupport\AdminSystemSupportController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {
    Route::group(['prefix' => 'AdminSystemSupport', 'as' => 'admin.systemSupport.', 'middleware' => ['AdminSystemSupportMid']], function () {
        Route::get('/', [AdminSystemSupportController::class, 'index'])->name('index');
        Route::get('/GetTickets', [AdminSystemSupportController::class, 'getTickets'])->name('getTickets');
        Route::post('ReplyTicket', [AdminSystemSupportController::class, 'replyTicket'])->name('replyTicket');
        Route::get('TicketDetails/{TicketID}', [AdminSystemSupportController::class, 'ticketDetails'])->name('TicketDetails');
        Route::post('RedirectTicket', [AdminSystemSupportController::class, 'redirectTicket'])->name('redirectTicket');
        Route::post('UpdateStatusTicket', [AdminSystemSupportController::class, 'updateStatusTicket'])->name('updateStatusTicket');
        Route::post('PageRowCount', [AdminSystemSupportController::class, 'pageRowCount'])->name('pageRowCount');
    });
});
