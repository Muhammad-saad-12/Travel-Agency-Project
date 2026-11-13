<?php

namespace App\Livewire\Admin\Analytics;

use App\Services\DashboardAnalyticsService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.admin')]
#[Title('Advanced Analytics & Reporting (Module 15)')]
class AnalyticsReporting extends Component
{
    public function exportCsv(DashboardAnalyticsService $service): StreamedResponse
    {
        return $service->exportAnalyticsCsv();
    }

    public function render(DashboardAnalyticsService $service)
    {
        $kpis = $service->getExecutiveKpis();
        $revenueData = $service->getRevenueAnalytics();
        $acquisition = $service->getCustomerAcquisitionMetrics();

        return view('livewire.admin.analytics.analytics-reporting', [
            'kpis' => $kpis,
            'revenueData' => $revenueData,
            'acquisition' => $acquisition,
        ]);
    }
}
