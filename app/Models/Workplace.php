<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    public function scopeActive(Builder $query): Builder
    {
        return $query->where(function (Builder $builder) {
            $builder->whereNull('to_date')->orWhereDate('to_date', '>=', today());
        });
    }

    public function scopeOrderedForList(Builder $query): Builder
    {
        return $query
            ->orderByRaw('to_date is null desc')
            ->orderByDesc('to_date')
            ->orderByDesc('from_date');
    }

    public function scopeMatchesFilters(Builder $query, ?int $companyId, ?int $occupationId): Builder
    {
        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        if ($occupationId) {
            $query->where('occupation_id', $occupationId);
        }

        return $query;
    }
}
