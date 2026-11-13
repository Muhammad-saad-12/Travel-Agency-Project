<?php

namespace App\Livewire\Public;

use App\Services\FlightInquiryService;
use Livewire\Component;

class FlightInquiryForm extends Component
{
    public string $trip_type = 'round_trip';

    public string $origin = 'Islamabad (ISB)';

    public string $destination = 'Jeddah (JED)';

    public string $departure_date = '';

    public ?string $return_date = '';

    /**
     * Multi-city segment legs.
     *
     * @var list<array{origin: string, destination: string, date: string}>
     */
    public array $multi_city_segments = [];

    public int $adults = 1;

    public int $children = 0;

    public int $infants = 0;

    public string $cabin_class = 'Economy';

    public string $customer_name = '';

    public string $customer_phone = '';

    public string $customer_email = '';

    public string $notes = '';

    public bool $showSuccessModal = false;

    public ?string $generatedReference = null;

    public function mount(): void
    {
        $this->departure_date = now()->addDays(7)->format('Y-m-d');
        $this->return_date = now()->addDays(14)->format('Y-m-d');

        $this->multi_city_segments = [
            ['origin' => 'Islamabad (ISB)', 'destination' => 'Dubai (DXB)', 'date' => now()->addDays(7)->format('Y-m-d')],
            ['origin' => 'Dubai (DXB)', 'destination' => 'Baku (GYD)', 'date' => now()->addDays(12)->format('Y-m-d')],
        ];
    }

    /**
     * Dynamic validation rules.
     *
     * @return array<string, string>
     */
    protected function rules(): array
    {
        $rules = [
            'trip_type' => 'required|in:one_way,round_trip,multi_city',
            'origin' => 'required|string|min:2|max:100',
            'destination' => 'required|string|min:2|max:100|different:origin',
            'departure_date' => 'required|date|after_or_equal:today',
            'adults' => 'required|integer|min:1|max:9',
            'children' => 'required|integer|min:0|max:9',
            'infants' => 'required|integer|min:0|lte:adults',
            'cabin_class' => 'required|in:Economy,Premium Economy,Business,First',
            'customer_name' => 'required|string|min:3|max:100',
            'customer_phone' => 'required|string|min:10|max:25',
            'customer_email' => 'required|email|max:150',
            'notes' => 'nullable|string|max:1000',
        ];

        if ($this->trip_type === 'round_trip') {
            $rules['return_date'] = 'required|date|after_or_equal:departure_date';
        } else {
            $rules['return_date'] = 'nullable|date';
        }

        if ($this->trip_type === 'multi_city') {
            $rules['multi_city_segments'] = 'required|array|min:2';
            $rules['multi_city_segments.*.origin'] = 'required|string';
            $rules['multi_city_segments.*.destination'] = 'required|string';
            $rules['multi_city_segments.*.date'] = 'required|date|after_or_equal:today';
        }

        return $rules;
    }

    protected $validationAttributes = [
        'customer_name' => 'Full Name',
        'customer_phone' => 'Contact Number',
        'customer_email' => 'Email Address',
        'departure_date' => 'Departure Date',
        'return_date' => 'Return Date',
        'multi_city_segments.*.origin' => 'Segment Origin',
        'multi_city_segments.*.destination' => 'Segment Destination',
        'multi_city_segments.*.date' => 'Segment Date',
    ];

    public function updated(string $propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function updatedTripType(string $value): void
    {
        if ($value === 'one_way') {
            $this->return_date = null;
        } elseif ($value === 'round_trip' && empty($this->return_date)) {
            $this->return_date = now()->addDays(14)->format('Y-m-d');
        }
        $this->resetValidation();
    }

    public function addSegment(): void
    {
        $lastDate = end($this->multi_city_segments)['date'] ?? now()->addDays(10)->format('Y-m-d');
        $this->multi_city_segments[] = [
            'origin' => '',
            'destination' => '',
            'date' => date('Y-m-d', strtotime($lastDate.' + 4 days')),
        ];
    }

    public function removeSegment(int $index): void
    {
        if (count($this->multi_city_segments) > 2) {
            unset($this->multi_city_segments[$index]);
            $this->multi_city_segments = array_values($this->multi_city_segments);
        }
    }

    public function submit(FlightInquiryService $service): void
    {
        $validated = $this->validate();

        $inquiry = $service->createInquiry([
            'trip_type' => $validated['trip_type'],
            'origin' => $validated['origin'],
            'destination' => $validated['destination'],
            'departure_date' => $validated['departure_date'],
            'return_date' => $validated['trip_type'] === 'round_trip' ? $validated['return_date'] : null,
            'multi_city_segments' => $validated['trip_type'] === 'multi_city' ? $validated['multi_city_segments'] : null,
            'adults' => $validated['adults'],
            'children' => $validated['children'],
            'infants' => $validated['infants'],
            'cabin_class' => $validated['cabin_class'],
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'],
            'notes' => $validated['notes'],
            'status' => 'Pending',
        ]);

        $this->generatedReference = $inquiry->reference_no;
        $this->showSuccessModal = true;
    }

    public function closeSuccessModal(): void
    {
        $this->showSuccessModal = false;
        $this->reset(['customer_name', 'customer_phone', 'customer_email', 'notes', 'generatedReference']);
    }

    public function render()
    {
        return view('livewire.public.flight-inquiry-form');
    }
}
