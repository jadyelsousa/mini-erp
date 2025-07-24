<?php

use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

// Rotas de webhook
Route::post('/webhook', [WebhookController::class, 'handle'])->name('webhook');
