<?php

namespace Tests\Feature;

use App\Livewire\Admin\AgencySettings;
use App\Models\AgencySetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AgencySettingsLivewireTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_settings_page_is_accessible(): void
    {
        $response = $this->get('/admin/settings');

        $response->assertStatus(200);
        $response->assertSee('Agency Settings');
        $response->assertSee('Module 16');
        $response->assertSee('PKR');
    }

    public function test_component_mounts_with_existing_agency_settings(): void
    {
        AgencySetting::factory()->create([
            'business_name' => 'Indus Aviation Services',
            'dts_license' => 'DTS-7711',
            'default_currency' => 'PKR',
        ]);

        Livewire::test(AgencySettings::class)
            ->assertSet('business_name', 'Indus Aviation Services')
            ->assertSet('dts_license', 'DTS-7711')
            ->assertSet('default_currency', 'PKR');
    }

    public function test_realtime_validation_requires_business_name(): void
    {
        Livewire::test(AgencySettings::class)
            ->set('business_name', '')
            ->assertHasErrors(['business_name' => 'required']);
    }

    public function test_realtime_validation_validates_business_name_minimum_length(): void
    {
        Livewire::test(AgencySettings::class)
            ->set('business_name', 'AB')
            ->assertHasErrors(['business_name' => 'min']);
    }

    public function test_realtime_validation_validates_email_format(): void
    {
        Livewire::test(AgencySettings::class)
            ->set('email', 'not-an-email')
            ->assertHasErrors(['email' => 'email']);
    }

    public function test_realtime_validation_validates_currency_code_size(): void
    {
        Livewire::test(AgencySettings::class)
            ->set('default_currency', 'PK')
            ->assertHasErrors(['default_currency' => 'size']);
    }

    public function test_settings_can_be_saved_successfully(): void
    {
        Livewire::test(AgencySettings::class)
            ->set('business_name', 'Aero Travel Global Ltd')
            ->set('dts_license', 'DTS-1234')
            ->set('ntn', '7891234-5')
            ->set('strn', '3277876543210')
            ->set('phone', '+92 51 5551234')
            ->set('email', 'support@aerotravel.pk')
            ->set('address', 'Plot 44, Sector F-7, Islamabad')
            ->set('whatsapp_number', '+92 300 5551234')
            ->set('default_currency', 'PKR')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('savedSuccessfully', true)
            ->assertSee('Settings Saved');

        $this->assertDatabaseHas('agency_settings', [
            'business_name' => 'Aero Travel Global Ltd',
            'dts_license' => 'DTS-1234',
            'ntn' => '7891234-5',
            'default_currency' => 'PKR',
        ]);
    }
}
