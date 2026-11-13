<?php

namespace App\Livewire\Admin;

use App\Services\AgencySettingService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Agency Settings (Module 16)')]
class AgencySettings extends Component
{
    #[Validate('required|string|min:3|max:255', as: 'Business Name')]
    public string $business_name = '';

    #[Validate('nullable|string|max:100', as: 'DTS License')]
    public ?string $dts_license = '';

    #[Validate('nullable|string|max:50', as: 'NTN')]
    public ?string $ntn = '';

    #[Validate('nullable|string|max:50', as: 'STRN')]
    public ?string $strn = '';

    #[Validate('nullable|string|max:30', as: 'Official Phone')]
    public ?string $phone = '';

    #[Validate('nullable|email|max:255', as: 'Official Email')]
    public ?string $email = '';

    #[Validate('nullable|string|max:500', as: 'Physical Address')]
    public ?string $address = '';

    #[Validate('nullable|string|max:30', as: 'WhatsApp Number')]
    public ?string $whatsapp_number = '';

    #[Validate('required|string|size:3', as: 'Default Currency')]
    public string $default_currency = 'PKR';

    public bool $savedSuccessfully = false;

    /**
     * Mount the component and populate properties from the database.
     */
    public function mount(AgencySettingService $service): void
    {
        $settings = $service->getSettings();

        $this->business_name = (string) ($settings->business_name ?? '');
        $this->dts_license = (string) ($settings->dts_license ?? '');
        $this->ntn = (string) ($settings->ntn ?? '');
        $this->strn = (string) ($settings->strn ?? '');
        $this->phone = (string) ($settings->phone ?? '');
        $this->email = (string) ($settings->email ?? '');
        $this->address = (string) ($settings->address ?? '');
        $this->whatsapp_number = (string) ($settings->whatsapp_number ?? '');
        $this->default_currency = (string) ($settings->default_currency ?? config('app.currency', 'PKR'));
    }

    /**
     * Real-time validation hook triggered on each updated property.
     */
    public function updated(string $propertyName): void
    {
        $this->savedSuccessfully = false;
        $this->validateOnly($propertyName);
    }

    /**
     * Persist updated settings via AgencySettingService.
     */
    public function save(AgencySettingService $service): void
    {
        $validated = $this->validate();

        $service->updateSettings([
            'business_name' => $validated['business_name'],
            'dts_license' => $validated['dts_license'] ?: null,
            'ntn' => $validated['ntn'] ?: null,
            'strn' => $validated['strn'] ?: null,
            'phone' => $validated['phone'] ?: null,
            'email' => $validated['email'] ?: null,
            'address' => $validated['address'] ?: null,
            'whatsapp_number' => $validated['whatsapp_number'] ?: null,
            'default_currency' => strtoupper($validated['default_currency']),
        ]);

        $this->default_currency = strtoupper($this->default_currency);
        $this->savedSuccessfully = true;

        session()->flash('status', 'Agency settings for Module 16 saved successfully.');
    }

    /**
     * Reset fields to default values.
     */
    public function resetToDefaults(AgencySettingService $service): void
    {
        $this->default_currency = 'PKR';
        $this->savedSuccessfully = false;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.agency-settings');
    }
}
