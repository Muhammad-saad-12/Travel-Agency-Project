<?php

namespace Tests\Feature;

use App\Models\AgencySetting;
use App\Services\AgencySettingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgencySettingServiceTest extends TestCase
{
    use RefreshDatabase;

    private AgencySettingService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(AgencySettingService::class);
    }

    public function test_get_settings_creates_default_record_when_none_exists(): void
    {
        $this->assertDatabaseEmpty('agency_settings');

        $settings = $this->service->getSettings();

        $this->assertInstanceOf(AgencySetting::class, $settings);
        $this->assertEquals('PKR', $settings->default_currency);
        $this->assertDatabaseCount('agency_settings', 1);
    }

    public function test_get_settings_retrieves_existing_record(): void
    {
        AgencySetting::factory()->create([
            'business_name' => 'Falcon Wings Air Travel',
            'default_currency' => 'PKR',
        ]);

        $settings = $this->service->getSettings();

        $this->assertEquals('Falcon Wings Air Travel', $settings->business_name);
        $this->assertEquals('PKR', $settings->default_currency);
    }

    public function test_update_settings_persists_changes(): void
    {
        $updated = $this->service->updateSettings([
            'business_name' => 'Skylink Travel Global',
            'dts_license' => 'DTS-9988',
            'ntn' => '1234567-8',
            'strn' => '9876543210123',
            'phone' => '+92 51 9876543',
            'email' => 'booking@skylink.pk',
            'address' => 'Floor 2, Blue Area, Islamabad',
            'whatsapp_number' => '+92 300 9876543',
            'default_currency' => 'PKR',
        ]);

        $this->assertEquals('Skylink Travel Global', $updated->business_name);
        $this->assertEquals('DTS-9988', $updated->dts_license);
        $this->assertEquals('1234567-8', $updated->ntn);
        $this->assertEquals('PKR', $updated->default_currency);

        $this->assertDatabaseHas('agency_settings', [
            'business_name' => 'Skylink Travel Global',
            'dts_license' => 'DTS-9988',
            'default_currency' => 'PKR',
        ]);
    }

    public function test_get_default_currency_returns_pkr_by_default(): void
    {
        $currency = $this->service->getDefaultCurrency();

        $this->assertEquals('PKR', $currency);
    }
}
