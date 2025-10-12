<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Сущность телефона организации.
 *
 * @property int $id
 * @property int $organization_id
 * @property string $phone
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read Organization $organization
 */
class OrganizationPhone extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'phone',
    ];


    /**
     * Организация-владелец телефона.
     *
     * @return BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
