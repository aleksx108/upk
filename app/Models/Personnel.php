<?php

namespace App\Models;

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
}
