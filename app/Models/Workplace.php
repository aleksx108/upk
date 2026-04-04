<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workplace extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'personnel_id',
        'company_id',
        'occupation_id',
        'from_date',
        'to_date',
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
    ];

    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function occupation(): BelongsTo
    {
        return $this->belongsTo(Occupation::class);
    }
}
