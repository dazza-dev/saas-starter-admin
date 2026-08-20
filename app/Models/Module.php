<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'icon', 'order'])]
class Module extends Model
{
    use HasUuid;

    protected $casts = [
        'order' => 'integer',
    ];

    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class);
    }
}
