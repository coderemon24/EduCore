<?php

use Illuminate\Support\Facades\Route;

// Redirect root to Admin Dashboard
Route::redirect('/', '/admin/dashboard');
