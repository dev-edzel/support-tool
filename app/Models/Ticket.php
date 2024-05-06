<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Ticket extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    protected $fillable = [
        'ticket_info_id',
        'ticket_number',
        'status',
        'assigned_to',
        'resolved_date',
        'last_modified_log_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function ticket_info(): BelongsTo
    {
        return $this->belongsTo(TicketInfo::class);
    }

    public function ticket_type(): BelongsTo
    {
        return $this->belongsTo(TicketType::class);
    }

    public function last_modified_log(): HasOne
    {
        return $this->hasOne(UserLog::class, 'id', 'last_modified_log_id');
    }

    public function toSearchableArray()
    {
        return [
            'ticket_number' => $this->ticket_number,
        ];
    }
}
