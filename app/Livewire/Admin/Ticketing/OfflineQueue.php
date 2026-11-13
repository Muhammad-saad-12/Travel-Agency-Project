<?php

namespace App\Livewire\Admin\Ticketing;

use App\Models\OfflineRequest;
use App\Services\OfflineTicketingService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Offline Ticketing & Refund Queue (Modules 15 & 16)')]
class OfflineQueue extends Component
{
    use WithPagination;

    #[Url]
    public string $requestType = 'all';

    #[Url]
    public string $status = 'all';

    #[Url]
    public string $airline = 'all';

    #[Url]
    public string $search = '';

    public string $urgentOnly = '';

    // Modals
    public bool $showTicketingModal = false;

    public bool $showRefundModal = false;

    public bool $showAssignModal = false;

    public ?int $selectedRequestId = null;

    // Ticketing form
    public string $ticketNumber = '';

    public string $assignedUser = '';

    public string $ticketingRemarks = '';

    // Refund form
    public float|int|string $refundAmount = '';

    public float|int|string $penaltyAmount = 0;

    public string $refundRemarks = '';

    // Assign form
    public string $assigneeName = '';

    // Feedback
    public ?string $successMessage = null;

    public ?string $errorMessage = null;

    public function updatingRequestType(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingAirline(): void
    {
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingUrgentOnly(): void
    {
        $this->resetPage();
    }

    public function setRequestType(string $type): void
    {
        $this->requestType = $type;
        $this->resetPage();
    }

    public function toggleUrgent(int $id, OfflineTicketingService $service): void
    {
        $req = OfflineRequest::findOrFail($id);
        $updated = $service->toggleUrgent($req);
        $this->successMessage = "Request #{$updated->id} urgency toggled: ".($updated->is_urgent ? 'Marked URGENT' : 'Normal priority');
    }

    public function openTicketingModal(int $id): void
    {
        $req = OfflineRequest::findOrFail($id);
        $this->selectedRequestId = $req->id;
        $this->ticketNumber = $req->ticket_number ?? '';
        $this->assignedUser = $req->assigned_user ?? auth()->user()->name ?? 'Ticketing Desk';
        $this->ticketingRemarks = $req->remarks ?? '';
        $this->showTicketingModal = true;
    }

    public function closeTicketingModal(): void
    {
        $this->showTicketingModal = false;
        $this->selectedRequestId = null;
        $this->ticketNumber = '';
        $this->ticketingRemarks = '';
    }

    public function processTicketing(OfflineTicketingService $service): void
    {
        $this->validate([
            'ticketNumber' => ['required', 'string', 'min:3', 'max:50'],
            'assignedUser' => ['required', 'string', 'max:100'],
            'ticketingRemarks' => ['nullable', 'string', 'max:500'],
        ]);

        if (! $this->selectedRequestId) {
            return;
        }

        $req = OfflineRequest::findOrFail($this->selectedRequestId);
        $service->processTicketing($req, $this->ticketNumber, $this->assignedUser, $this->ticketingRemarks);

        $this->successMessage = "Ticketing request for PNR {$req->pnr} completed with E-Ticket {$this->ticketNumber}!";
        $this->closeTicketingModal();
    }

    public function openRefundModal(int $id): void
    {
        $req = OfflineRequest::findOrFail($id);
        $this->selectedRequestId = $req->id;
        $this->refundAmount = (float) $req->refund_amount;
        $this->penaltyAmount = (float) $req->penalty_amount;
        $this->refundRemarks = $req->remarks ?? '';
        $this->showRefundModal = true;
    }

    public function closeRefundModal(): void
    {
        $this->showRefundModal = false;
        $this->selectedRequestId = null;
        $this->refundAmount = '';
        $this->penaltyAmount = 0;
        $this->refundRemarks = '';
    }

    public function processRefund(OfflineTicketingService $service): void
    {
        $this->validate([
            'refundAmount' => ['required', 'numeric', 'min:0'],
            'penaltyAmount' => ['required', 'numeric', 'min:0'],
            'refundRemarks' => ['nullable', 'string', 'max:500'],
        ]);

        if (! $this->selectedRequestId) {
            return;
        }

        $req = OfflineRequest::findOrFail($this->selectedRequestId);
        $service->processRefund($req, (float) $this->refundAmount, (float) $this->penaltyAmount, $this->refundRemarks);

        $this->successMessage = "Refund request for PNR {$req->pnr} processed! Refund: PKR ".number_format((float) $this->refundAmount, 2);
        $this->closeRefundModal();
    }

    public function openAssignModal(int $id): void
    {
        $req = OfflineRequest::findOrFail($id);
        $this->selectedRequestId = $req->id;
        $this->assigneeName = $req->assigned_user ?? 'Desk Agent';
        $this->showAssignModal = true;
    }

    public function closeAssignModal(): void
    {
        $this->showAssignModal = false;
        $this->selectedRequestId = null;
        $this->assigneeName = '';
    }

    public function assignRequest(OfflineTicketingService $service): void
    {
        $this->validate([
            'assigneeName' => ['required', 'string', 'min:2', 'max:100'],
        ]);

        if (! $this->selectedRequestId) {
            return;
        }

        $req = OfflineRequest::findOrFail($this->selectedRequestId);
        $service->assignUser($req, $this->assigneeName);

        $this->successMessage = "Request assigned to {$this->assigneeName}.";
        $this->closeAssignModal();
    }

    public function updateStatus(int $id, string $newStatus, OfflineTicketingService $service): void
    {
        $req = OfflineRequest::findOrFail($id);
        $service->updateStatus($req, $newStatus);
        $this->successMessage = "Request #{$req->id} status updated to {$newStatus}.";
    }

    public function render(OfflineTicketingService $service)
    {
        $filters = [
            'request_type' => $this->requestType,
            'status' => $this->status,
            'airline' => $this->airline,
            'search' => $this->search,
            'is_urgent' => $this->urgentOnly === '1' ? true : null,
        ];

        $requests = $service->listRequests($filters, 12);
        $statistics = $service->getStatistics();
        $airlines = $service->getAirlinesList();

        return view('livewire.admin.ticketing.offline-queue', [
            'requests' => $requests,
            'statistics' => $statistics,
            'airlines' => $airlines,
        ]);
    }
}
