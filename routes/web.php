<?php

use App\Livewire\Admin\AgencySettings;
use App\Livewire\Admin\Analytics\AnalyticsReporting;
use App\Livewire\Admin\Bookings\BookingManager;
use App\Livewire\Admin\Dashboard\AdminDashboard;
use App\Livewire\Admin\Destinations\DestinationManager;
use App\Livewire\Admin\Gds\PnrImporter;
use App\Livewire\Admin\Hotels\HotelManager;
use App\Livewire\Admin\Inquiries\InquiryInbox;
use App\Livewire\Admin\Ledger\DepositLedger;
use App\Livewire\Admin\Packages\PackageManager;
use App\Livewire\Admin\Payments\PaymentManager;
use App\Livewire\Admin\Ticketing\OfflineQueue;
use App\Livewire\Public\HomePage;
use Illuminate\Support\Facades\Route;

// Public Customer Portal (Module 1)
Route::get('/', HomePage::class)->name('home');

// Admin Operations (Modules 1 - 18)
Route::prefix('admin')->name('admin.')->group(function (): void {
    // Command Center & Analytics (Modules 3 & 15)
    Route::get('/', AdminDashboard::class)->name('dashboard');
    Route::get('/dashboard', AdminDashboard::class);
    Route::get('/analytics', AnalyticsReporting::class)->name('analytics');

    // Operations & Transaction Engines
    Route::get('/inquiries', InquiryInbox::class)->name('inquiries');
    Route::get('/bookings', BookingManager::class)->name('bookings');
    Route::get('/payments', PaymentManager::class)->name('payments');
    Route::get('/gds/importer', PnrImporter::class)->name('gds.importer');
    Route::get('/ticketing/queue', OfflineQueue::class)->name('ticketing.queue');
    Route::get('/ledger', DepositLedger::class)->name('ledger');

    // Catalog & Content
    Route::get('/destinations', DestinationManager::class)->name('destinations');
    Route::get('/hotels', HotelManager::class)->name('hotels');
    Route::get('/packages', PackageManager::class)->name('packages');

    // System Settings
    Route::get('/settings', AgencySettings::class)->name('settings');
});
