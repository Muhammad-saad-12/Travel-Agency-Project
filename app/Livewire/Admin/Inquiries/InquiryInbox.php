<?php

namespace App\Livewire\Admin\Inquiries;

use App\Models\FlightInquiry;
use App\Services\FlightInquiryService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Flight Inquiry Inbox (Module 5)')]
class InquiryInbox extends Component
{
    use WithPagination;

    public string $statusFilter = 'All';

    public string $search = '';

    public string $tripTypeFilter = '';

    // Modals
    public bool $showDetailModal = false;

    public bool $showQuoteModal = false;

    public ?int $selectedInquiryId = null;

    // Fast quote form
    public float|int|string $quotePrice = '';

    public string $quoteStatus = 'Quoted';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function setStatusFilter(string $status): void
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    public function openDetailModal(int $id): void
    {
        $this->selectedInquiryId = $id;
        $this->showDetailModal = true;
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->selectedInquiryId = null;
    }

    public function openQuoteModal(int $id, FlightInquiryService $service): void
    {
        $inquiry = $service->findById($id);
        $this->selectedInquiryId = $inquiry->id;
        $this->quotePrice = (float) ($inquiry->quoted_price_pkr ?? '');
        $this->quoteStatus = $inquiry->status === 'Pending' ? 'Quoted' : $inquiry->status;
        $this->showQuoteModal = true;
    }

    public function saveQuote(FlightInquiryService $service): void
    {
        $this->validate([
            'quotePrice' => 'required|numeric|min:0',
            'quoteStatus' => 'required|in:Pending,Contacted,Quoted,Booked',
        ]);

        if ($this->selectedInquiryId) {
            $service->updateQuote($this->selectedInquiryId, (float) $this->quotePrice, $this->quoteStatus);
            session()->flash('status', 'Quotation updated and inquiry marked as '.$this->quoteStatus.'.');
            $this->showQuoteModal = false;
        }
    }

    public function updateQuickStatus(int $id, string $status, FlightInquiryService $service): void
    {
        $service->updateStatus($id, $status);
        session()->flash('status', 'Inquiry status changed to '.$status.'.');
    }

    public function deleteInquiry(int $id, FlightInquiryService $service): void
    {
        $service->delete($id);
        session()->flash('status', 'Flight inquiry removed.');
    }

    public function render(FlightInquiryService $service)
    {
        $counts = $service->getStatusCounts();

        $inquiries = $service->list([
            'status' => $this->statusFilter,
            'search' => $this->search,
            'trip_type' => $this->tripTypeFilter,
        ], 10);

        $selectedInquiry = $this->selectedInquiryId ? FlightInquiry::query()->find($this->selectedInquiryId) : null;

        return view('livewire.admin.inquiries.inquiry-inbox', [
            'inquiries' => $inquiries,
            'counts' => $counts,
            'selectedInquiry' => $selectedInquiry,
        ]);
    }
}
