<?php

namespace App\Services;

use App\Models\AgencySetting;
use Illuminate\Support\Facades\Cache;

class AgencySettingService
{
    /**
     * Cache key for agency settings.
     */
    public const CACHE_KEY = 'agency_settings.current';

    /**
     * Retrieve the current agency settings.
     * If no settings exist yet, a default record is created.
     */
    public function getSettings(): AgencySetting
    {
        return Cache::remember(self::CACHE_KEY, now()->addHours(6), function (): AgencySetting {
            return AgencySetting::query()->first() ?? AgencySetting::query()->create([
                'business_name' => config('app.name', 'Flight Booking Agency'),
                'default_currency' => config('app.currency', 'PKR'),
            ]);
        });
    }

    /**
     * Update the current agency settings with validated data.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateSettings(array $data): AgencySetting
    {
        $settings = AgencySetting::query()->first();

        if ($settings) {
            $settings->update($data);
        } else {
            $settings = AgencySetting::query()->create($data);
        }

        Cache::forget(self::CACHE_KEY);

        return $settings->fresh();
    }

    /**
     * Get the default agency currency code (e.g. 'PKR').
     */
    public function getDefaultCurrency(): string
    {
        return $this->getSettings()->default_currency ?? config('app.currency', 'PKR');
    }

    /**
     * Clear cached settings.
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
