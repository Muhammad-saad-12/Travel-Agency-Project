<?php

namespace App\Models;

use Database\Factories\AgencySettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencySetting extends Model
{
    /** @use HasFactory<AgencySettingFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agency_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'business_name',
        'dts_license',
        'ntn',
        'strn',
        'phone',
        'email',
        'address',
        'whatsapp_number',
        'default_currency',
    ];

    /**
     * Default model attribute values.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'default_currency' => 'PKR',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'default_currency' => 'string',
        ];
    }
}
