<?php

namespace Tests\Feature;

use App\Livewire\Public\FlightInquiryForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FlightInquiryLivewireTest extends TestCase
{
    use RefreshDatabase;

    public function test_component_mounts_with_defaults(): void
    {
        Livewire::test(FlightInquiryForm::class)
            ->assertSet('trip_type', 'round_trip')
            ->assertSet('adults', 1)
            ->assertSet('children', 0)
            ->assertSet('infants', 0)
            ->assertSet('cabin_class', 'Economy');
    }

    public function test_realtime_validation_requires_customer_details(): void
    {
        Livewire::test(FlightInquiryForm::class)
            ->set('customer_name', '')
            ->assertHasErrors(['customer_name' => 'required'])
            ->set('customer_phone', '')
            ->assertHasErrors(['customer_phone' => 'required'])
            ->set('customer_email', 'invalid-email')
            ->assertHasErrors(['customer_email' => 'email']);
    }

    public function test_date_validation_requires_return_date_after_departure(): void
    {
        Livewire::test(FlightInquiryForm::class)
            ->set('trip_type', 'round_trip')
            ->set('departure_date', now()->addDays(10)->format('Y-m-d'))
            ->set('return_date', now()->addDays(5)->format('Y-m-d'))
            ->assertHasErrors(['return_date' => 'after_or_equal']);
    }

    public function test_infants_cannot_exceed_adults(): void
    {
        Livewire::test(FlightInquiryForm::class)
            ->set('adults', 1)
            ->set('infants', 2)
            ->assertHasErrors(['infants' => 'lte']);
    }

    public function test_can_add_and_remove_multi_city_segments(): void
    {
        Livewire::test(FlightInquiryForm::class)
            ->set('trip_type', 'multi_city')
            ->assertCount('multi_city_segments', 2)
            ->call('addSegment')
            ->assertCount('multi_city_segments', 3)
            ->call('removeSegment', 2)
            ->assertCount('multi_city_segments', 2);
    }

    public function test_successful_submission_creates_inquiry_and_shows_modal(): void
    {
        Livewire::test(FlightInquiryForm::class)
            ->set('trip_type', 'round_trip')
            ->set('origin', 'Karachi (KHI)')
            ->set('destination', 'Dubai (DXB)')
            ->set('departure_date', now()->addDays(10)->format('Y-m-d'))
            ->set('return_date', now()->addDays(17)->format('Y-m-d'))
            ->set('adults', 2)
            ->set('children', 1)
            ->set('infants', 0)
            ->set('cabin_class', 'Business')
            ->set('customer_name', 'Asad Mehmood')
            ->set('customer_phone', '+92 321 9876543')
            ->set('customer_email', 'asad@mehmood.pk')
            ->set('notes', 'Emirates flight required')
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('showSuccessModal', true);

        $this->assertDatabaseHas('flight_inquiries', [
            'origin' => 'Karachi (KHI)',
            'destination' => 'Dubai (DXB)',
            'customer_name' => 'Asad Mehmood',
            'cabin_class' => 'Business',
            'status' => 'Pending',
        ]);
    }
}
