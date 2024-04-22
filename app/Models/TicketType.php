<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class TicketType extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    protected $table = 'ticket_type';

    protected $fillable = [
        'short_name',
        'name',
        'last_modified_log_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
    public function last_modified_log(): HasOne
    {
        return $this->hasOne(UserLog::class, 'id', 'last_modified_log_id');
    }

    public function toSearchableArray()
    {
        return [
            'short_name' => $this->short_name,
            'name' => $this->name
        ];
    }
}
