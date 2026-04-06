<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Personnel extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;

    protected $table = 'personnel';

    protected $fillable = [
        'first_name',
        'last_name',
        'personal_code',
        'gender',
        'birthday_date',
        'phone_number',
        'email',
        'country_code',
        'postal_code',
        'city',
        'street',
        'street_number',
        'notes',
    ];

    protected $casts = [
        'birthday_date' => 'date',
    ];

    protected $appends = [
        'portrait_photo_url',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('portrait_photo')->singleFile();
    }

    public function getPortraitPhotoUrlAttribute(): ?string
    {
        $url = $this->getFirstMediaUrl('portrait_photo');

        return $url !== '' ? $url : null;
    }

    public function workplaces(): HasMany
    {
        return $this->hasMany(Workplace::class);
    }

    public function scopeForIndex(Builder $query, string $search, ?int $companyId, ?int $occupationId): Builder
    {
        return $query
            ->with([
                'media',
                'workplaces' => function ($builder) {
                    $builder
                        ->with(['company', 'occupation'])
                        ->active()
                        ->orderedForList();
                },
            ])
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->search($search)
            ->filterByActiveWorkplace($companyId, $occupationId);
    }

    public function scopeSearch(Builder $query, string $search): Builder
    {
        if ($search === '') {
            return $query;
        }

        return $query->where(function (Builder $builder) use ($search) {
            $builder
                ->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('personal_code', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone_number', 'like', "%{$search}%")
                ->orWhere('city', 'like', "%{$search}%")
                ->orWhere('street', 'like', "%{$search}%");
        });
    }

    public function scopeFilterByActiveWorkplace(Builder $query, ?int $companyId, ?int $occupationId): Builder
    {
        if (! $companyId && ! $occupationId) {
            return $query;
        }

        return $query->whereHas('workplaces', function (Builder $builder) use ($companyId, $occupationId) {
            $builder
                ->active()
                ->matchesFilters($companyId, $occupationId);
        });
    }
}
