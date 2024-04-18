<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientInfo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'client_info';

    protected $fillable = [
        'ticket_id',
        'first_name',
        'middle_name',
        'last_name',
        'address',
        'number',
        'email'
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
}
