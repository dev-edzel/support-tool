<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['type'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function sub_categories(): HasMany
    {
        return $this->hasMany(SubCategory::class);
    }
}
