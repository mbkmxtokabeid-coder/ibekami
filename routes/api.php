<?php

use App\Http\Controllers\WhatsAppWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

// WhatsApp Webhook (Meta Cloud API)
Route::prefix('whatsapp')->group(function () {
    Route::get('/webhook', [WhatsAppWebhookController::class, 'verify'])->name('whatsapp.webhook.verify');
    Route::post('/webhook', [WhatsAppWebhookController::class, 'handleWebhook'])->name('whatsapp.webhook.handle');
});
