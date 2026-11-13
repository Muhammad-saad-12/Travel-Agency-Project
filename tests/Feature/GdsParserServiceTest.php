<?php

namespace Tests\Feature;

use App\Livewire\Admin\Gds\PnrImporter;
use App\Services\GdsParserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class GdsParserServiceTest extends TestCase
{
    use RefreshDatabase;

    private GdsParserService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(GdsParserService::class);
    }

    public function test_can_decode_amadeus_pnr(): void
    {
        $samples = GdsParserService::getSamplePnrs();
        $amadeusSample = $samples['amadeus']['pnr'];

        $parsed = $this->service->parse($amadeusSample);

        $this->assertEquals('amadeus', $parsed['system']);
        $this->assertEquals('PK7X9Z', $parsed['pnr_code']);
        $this->assertNotEmpty($parsed['passengers']);
        $this->assertEquals('KHAN/MUHAMMAD MR', $parsed['passengers'][0]['name']);
        $this->assertNotEmpty($parsed['segments']);
        $this->assertEquals('PK', $parsed['segments'][0]['airline_code']);
        $this->assertEquals('ISB', $parsed['segments'][0]['origin']);
        $this->assertEquals('LHR', $parsed['segments'][0]['destination']);
        $this->assertGreaterThan(0, $parsed['fare_breakdown']['total_amount_pkr']);
        $this->assertNotEmpty($parsed['time_limit_raw']);
    }

    public function test_can_decode_sabre_pnr(): void
    {
        $samples = GdsParserService::getSamplePnrs();
        $sabreSample = $samples['sabre']['pnr'];

        $parsed = $this->service->parse($sabreSample);

        $this->assertEquals('sabre', $parsed['system']);
        $this->assertEquals('EK8M2Q', $parsed['pnr_code']);
        $this->assertEquals('AHMED/BILAL MR', $parsed['passengers'][0]['name']);
        $this->assertEquals('EK', $parsed['segments'][0]['airline_code']);
        $this->assertEquals('KHI', $parsed['segments'][0]['origin']);
        $this->assertEquals('DXB', $parsed['segments'][0]['destination']);
        $this->assertGreaterThan(0, $parsed['fare_breakdown']['total_amount_pkr']);
    }

    public function test_can_decode_galileo_pnr(): void
    {
        $samples = GdsParserService::getSamplePnrs();
        $galileoSample = $samples['galileo']['pnr'];

        $parsed = $this->service->parse($galileoSample);

        $this->assertEquals('galileo', $parsed['system']);
        $this->assertEquals('QR4W1P', $parsed['pnr_code']);
        $this->assertEquals('ALI/ZAINAB MRS', $parsed['passengers'][0]['name']);
        $this->assertEquals('QR', $parsed['segments'][0]['airline_code']);
        $this->assertEquals('LHE', $parsed['segments'][0]['origin']);
        $this->assertEquals('DOH', $parsed['segments'][0]['destination']);
    }

    public function test_can_decode_fly_jinnah_pnr(): void
    {
        $samples = GdsParserService::getSamplePnrs();
        $fjSample = $samples['fly_jinnah']['pnr'];

        $parsed = $this->service->parse($fjSample);

        $this->assertEquals('fly_jinnah', $parsed['system']);
        $this->assertEquals('9PJ781', $parsed['pnr_code']);
        $this->assertEquals('MALIK/USMAN MR', $parsed['passengers'][0]['name']);
        $this->assertEquals('9P', $parsed['segments'][0]['airline_code']);
        $this->assertEquals('KHI', $parsed['segments'][0]['origin']);
        $this->assertEquals('ISB', $parsed['segments'][0]['destination']);
        $this->assertGreaterThan(0, $parsed['fare_breakdown']['total_amount_pkr']);
    }

    public function test_can_decode_airsial_pnr(): void
    {
        $samples = GdsParserService::getSamplePnrs();
        $pfSample = $samples['airsial']['pnr'];

        $parsed = $this->service->parse($pfSample);

        $this->assertEquals('airsial', $parsed['system']);
        $this->assertEquals('PF9102', $parsed['pnr_code']);
        $this->assertEquals('CHAUDHRY/HAMZA MR', $parsed['passengers'][0]['name']);
        $this->assertEquals('PF', $parsed['segments'][0]['airline_code']);
        $this->assertEquals('KHI', $parsed['segments'][0]['origin']);
        $this->assertEquals('LHE', $parsed['segments'][0]['destination']);
    }

    public function test_pnr_importer_page_loads_and_displays_form(): void
    {
        $response = $this->get('/admin/gds/importer');
        $response->assertStatus(200);
        $response->assertSee('GDS PNR Importer & Decoder');
        $response->assertSee('Modules 13 & 14');
    }

    public function test_pnr_importer_livewire_can_load_sample_and_create_booking(): void
    {
        Livewire::test(PnrImporter::class)
            ->call('loadSample', 'amadeus')
            ->assertSet('selectedSystem', 'amadeus')
            ->assertSee('PK7X9Z')
            ->assertSee('KHAN/MUHAMMAD MR')
            ->call('createBookingFromPnr')
            ->assertSee('successfully generated from PNR');

        $this->assertDatabaseHas('bookings', [
            'pnr' => 'PK7X9Z',
            'airline' => 'PK',
            'customer_name' => 'KHAN/MUHAMMAD MR',
        ]);
    }
}
