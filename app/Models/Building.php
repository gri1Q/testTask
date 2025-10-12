<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Сущность здания.
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property float $latitude
 * @property float $longitude
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read Collection|Organization[] $organizations
 */
class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];


    /**
     * Организации, находящиеся в здании.
     *
     * @return HasMany
     */
    public function organizations(): HasMany
    {
        return $this->hasMany(Organization::class);
    }
}
