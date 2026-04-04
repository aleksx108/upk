<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'registration_no',
        'country_code',
        'postal_code',
        'city',
        'street',
        'street_number',
    ];

    public function workplaces(): HasMany
    {
        return $this->hasMany(Workplace::class);
    }
}
