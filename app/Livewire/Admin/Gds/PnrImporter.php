<?php

namespace App\Livewire\Admin\Gds;

use App\Services\BookingService;
use App\Services\GdsParserService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('GDS PNR Importer & Decoder (Modules 13 & 14)')]
class PnrImporter extends Component
{
    public string $rawPnr = '';

    public string $selectedSystem = 'auto';

    /**
     * @var array<string, mixed>|null
     */
    public ?array $parsedResult = null;

    public ?string $errorMessage = null;

    public ?string $successMessage = null;

    public ?string $createdBookingRef = null;

    public function mount(): void
    {
        // Load default sample (Amadeus)
        $this->loadSample('amadeus');
    }

    public function loadSample(string $system): void
    {
        $this->errorMessage = null;
        $this->successMessage = null;
        $this->createdBookingRef = null;

        $samples = GdsParserService::getSamplePnrs();

        if (isset($samples[$system])) {
            $this->rawPnr = $samples[$system]['pnr'];
            $this->selectedSystem = $system;
            $this->parsePnr(app(GdsParserService::class));
        }
    }

    public function parsePnr(GdsParserService $service): void
    {
        $this->errorMessage = null;
        $this->successMessage = null;
        $this->createdBookingRef = null;

        $raw = trim($this->rawPnr);
        if (empty($raw)) {
            $this->errorMessage = 'Please paste a raw PNR string or select a sample.';
            $this->parsedResult = null;

            return;
        }

        try {
            $this->parsedResult = $service->parse($raw, $this->selectedSystem === 'auto' ? null : $this->selectedSystem);
        } catch (\Throwable $e) {
            $this->errorMessage = 'Unable to parse PNR: '.$e->getMessage();
            $this->parsedResult = null;
        }
    }

    public function createBookingFromPnr(BookingService $bookingService): void
    {
        if (empty($this->parsedResult)) {
            $this->errorMessage = 'No parsed PNR data available to create a booking.';

            return;
        }

        $res = $this->parsedResult;
        $firstSegment = $res['segments'][0] ?? null;
        $firstPassenger = $res['passengers'][0] ?? null;

        $origin = $firstSegment['origin'] ?? 'KHI';
        $destination = $firstSegment['destination'] ?? 'ISB';
        $route = "{$origin} - {$destination}";
        $airline = $firstSegment['airline_code'] ?? ($res['system'] ?? 'GDS');
        $flightNumber = $firstSegment['flight_number'] ?? '';
        $travelDate = ! empty($firstSegment['departure_date']) ? $firstSegment['departure_date'] : now()->toDateString();

        $fareBreakdown = $res['fare_breakdown'] ?? [];
        $fareAmount = (float) ($fareBreakdown['base_fare_pkr'] ?? 0);
        $taxAmount = (float) ($fareBreakdown['tax_amount_pkr'] ?? 0);
        $totalAmount = (float) ($fareBreakdown['total_amount_pkr'] ?? ($fareAmount + $taxAmount));

        $bookingData = [
            'pnr' => $res['pnr_code'] ?? 'PENDNG',
            'customer_name' => $firstPassenger['name'] ?? 'Walk-in Client',
            'customer_email' => 'booking.'.strtolower($res['pnr_code'] ?? 'guest').'@flightdesk.pk',
            'customer_phone' => '+92 300 0000000',
            'service' => 'Flight Ticket',
            'type' => 'flight',
            'destination' => $destination,
            'route' => $route,
            'airline' => $airline,
            'flight_number' => $flightNumber,
            'travel_date' => $travelDate,
            'passengers_count' => max(1, count($res['passengers'] ?? [1])),
            'fare_amount' => $fareAmount,
            'tax_amount' => $taxAmount,
            'ancillaries_amount' => 0,
            'markup_amount' => 0,
            'total_amount' => $totalAmount,
            'currency' => 'PKR',
            'status' => 'Confirmed',
            'payment_status' => 'Unpaid',
            'supplier' => ucfirst($res['system'] ?? 'Amadeus').' GDS',
            'time_limit' => $res['time_limit_datetime'] ?? null,
            'passengers_data' => $res['passengers'] ?? [],
            'segments_data' => $res['segments'] ?? [],
            'remarks' => 'Imported via GDS Decoder. PNR: '.($res['pnr_code'] ?? 'N/A'),
        ];

        $booking = $bookingService->createBooking($bookingData);

        $this->createdBookingRef = $booking->booking_reference;
        $this->successMessage = "Booking {$booking->booking_reference} successfully generated from PNR {$booking->pnr}!";
    }

    public function clear(): void
    {
        $this->rawPnr = '';
        $this->parsedResult = null;
        $this->errorMessage = null;
        $this->successMessage = null;
        $this->createdBookingRef = null;
    }

    public function render()
    {
        return view('livewire.admin.gds.pnr-importer', [
            'samplePnrs' => GdsParserService::getSamplePnrs(),
        ]);
    }
}
