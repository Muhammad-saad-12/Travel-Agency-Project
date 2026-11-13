<?php

namespace App\Livewire\Admin\Dashboard;

use App\Services\DashboardAnalyticsService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Admin Command Center & Dashboard (Module 3)')]
class AdminDashboard extends Component
{
    public function render(DashboardAnalyticsService $service)
    {
        $kpis = $service->getExecutiveKpis();
        $revenueData = $service->getRevenueAnalytics();
        $upcomingDepartures = $service->getUpcomingDepartures(6);
        $gdsMonitoring = $service->getGdsMonitoringStatus();
        $announcements = $service->getAirlineAnnouncements();

        return view('livewire.admin.dashboard.admin-dashboard', [
            'kpis' => $kpis,
            'revenueData' => $revenueData,
            'upcomingDepartures' => $upcomingDepartures,
            'gdsMonitoring' => $gdsMonitoring,
            'announcements' => $announcements,
        ]);
    }
}
