<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Personnel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'personnel';

    protected $fillable = [
        'first_name',
        'last_name',
        'personal_code',
        'gender',
        'phone_number',
        'email',
        'country_code',
        'postal_code',
        'city',
        'street',
        'street_number',
        'notes',
    ];
}