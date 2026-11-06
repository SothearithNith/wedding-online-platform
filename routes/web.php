<?php

use App\Livewire\Admin\AdminDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/admin', AdminDashboard::class)->name('admin');
