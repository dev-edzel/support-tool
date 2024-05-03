<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class SubCategory extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    protected $table = 'sub_categories';

    protected $fillable = [
        'type',
        'category_id',
        'last_modified_log_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function ticket_info(): HasOne
    {
        return $this->hasOne(TicketInfo::class);
    }

    public function last_modified_log(): HasOne
    {
        return $this->hasOne(UserLog::class, 'id', 'last_modified_log_id');
    }
}
