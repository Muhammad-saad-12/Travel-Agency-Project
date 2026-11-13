<?php

namespace App\Livewire\Admin\Bookings;

use App\Models\Booking;
use App\Services\BookingService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.admin')]
#[Title('Centralized Booking Management (Modules 7 & 8)')]
class BookingManager extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $type = 'all';

    #[Url]
    public string $status = 'all';

    #[Url]
    public string $payment_status = 'all';

    public string $date_from = '';

    public string $date_to = '';

    // Modals
    public bool $showDetailModal = false;

    public bool $showTicketModal = false;

    public bool $showStatusModal = false;

    public ?int $selectedBookingId = null;

    // Ticket issuance state
    public string $ticketRef = '';

    // Status change state
    public string $newStatus = 'Confirmed';

    public string $statusRemarks = '';

    // Feedback
    public ?string $successMessage = null;

    public ?string $errorMessage = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingType(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingPaymentStatus(): void
    {
        $this->resetPage();
    }

    public function updatingDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatingDateTo(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->type = 'all';
        $this->status = 'all';
        $this->payment_status = 'all';
        $this->date_from = '';
        $this->date_to = '';
        $this->resetPage();
    }

    public function openDetailModal(int $id): void
    {
        $this->selectedBookingId = $id;
        $this->showDetailModal = true;
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->selectedBookingId = null;
    }

    public function openTicketModal(int $id): void
    {
        $booking = Booking::findOrFail($id);
        $this->selectedBookingId = $booking->id;
        $this->ticketRef = $booking->ticket_ref ?? '';
        $this->showTicketModal = true;
    }

    public function closeTicketModal(): void
    {
        $this->showTicketModal = false;
        $this->selectedBookingId = null;
        $this->ticketRef = '';
    }

    public function issueTicket(BookingService $service): void
    {
        $this->validate([
            'ticketRef' => ['required', 'string', 'min:3', 'max:50'],
        ]);

        if (! $this->selectedBookingId) {
            return;
        }

        $booking = Booking::findOrFail($this->selectedBookingId);
        $service->issueTicket($booking, $this->ticketRef);

        $this->successMessage = "E-Ticket reference {$this->ticketRef} successfully issued for {$booking->booking_reference}!";
        $this->closeTicketModal();
    }

    public function openStatusModal(int $id): void
    {
        $booking = Booking::findOrFail($id);
        $this->selectedBookingId = $booking->id;
        $this->newStatus = $booking->status;
        $this->statusRemarks = $booking->remarks ?? '';
        $this->showStatusModal = true;
    }

    public function closeStatusModal(): void
    {
        $this->showStatusModal = false;
        $this->selectedBookingId = null;
        $this->statusRemarks = '';
    }

    public function updateStatus(BookingService $service): void
    {
        $this->validate([
            'newStatus' => ['required', 'string', 'in:Confirmed,Ticketed,Pending,Cancelled,Expired'],
            'statusRemarks' => ['nullable', 'string', 'max:500'],
        ]);

        if (! $this->selectedBookingId) {
            return;
        }

        $booking = Booking::findOrFail($this->selectedBookingId);
        $service->updateStatus($booking, $this->newStatus, $this->statusRemarks);

        $this->successMessage = "Booking {$booking->booking_reference} status updated to {$this->newStatus}.";
        $this->closeStatusModal();
    }

    public function exportCsv(BookingService $service): StreamedResponse
    {
        $filters = [
            'search' => $this->search,
            'type' => $this->type === 'all' ? null : $this->type,
            'status' => $this->status === 'all' ? null : $this->status,
            'payment_status' => $this->payment_status === 'all' ? null : $this->payment_status,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
        ];

        return $service->exportCsv($filters);
    }

    public function render(BookingService $service)
    {
        $filters = [
            'search' => $this->search,
            'type' => $this->type === 'all' ? null : $this->type,
            'status' => $this->status === 'all' ? null : $this->status,
            'payment_status' => $this->payment_status === 'all' ? null : $this->payment_status,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
        ];

        $bookings = $service->listBookings($filters, 12);
        $statistics = $service->getStatistics();
        $selectedBooking = $this->selectedBookingId ? Booking::find($this->selectedBookingId) : null;

        return view('livewire.admin.bookings.booking-manager', [
            'bookings' => $bookings,
            'statistics' => $statistics,
            'selectedBooking' => $selectedBooking,
        ]);
    }
}
