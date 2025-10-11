<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Сущность деятельности.
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int|null $parent_id
 * @property int $level
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read Activity|null $parent
 * @property-read Collection|Activity[] $children
 * @property-read Collection|Organization[] $organizations
 */
class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'parent_id',
        'level',
    ];

    protected $casts = [
        'parent_id' => 'int',
        'level' => 'int',
    ];

    /**
     * Родительская деятельность.
     *
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Activity::class, 'parent_id');
    }


    /**
     * Дочерние деятельности.
     *
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(Activity::class, 'parent_id');
    }

    /**
     * Организации, относящиеся к этой деятельности.
     *
     * @return BelongsToMany
     */
    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'organization_activity');
    }
}
