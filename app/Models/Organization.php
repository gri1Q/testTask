<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Сущность организации.
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $email
 * @property int $building_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read Building $building
 * @property-read Collection|OrganizationPhone[] $phones
 * @property-read Collection|Activity[] $activities
 */
class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'email',
        'building_id',
    ];

    /**
     * Здание, в котором находится организация.
     *
     * @return BelongsTo
     */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }


    /**
     * Телефоны организации.
     *
     * @return HasMany
     */
    public function phones(): HasMany
    {
        return $this->hasMany(OrganizationPhone::class);
    }

    /**
     * Виды деятельности.
     *
     * @return BelongsToMany
     */
    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class, 'organization_activity');
    }
}
