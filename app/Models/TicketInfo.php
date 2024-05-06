<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class TicketInfo extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    protected $table = 'ticket_info';

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'address',
        'number',
        'email',
        'ticket_type_id',
        'category_id',
        'sub_category_id',
        'subject',
        'ref_no',
        'concern',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function ticket(): HasOne
    {
        return $this->hasOne(Ticket::class);
    }

    public function ticket_type(): BelongsTo

    {
        return $this->belongsTo(TicketType::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function sub_category(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function last_modified_log(): HasOne
    {
        return $this->hasOne(UserLog::class, 'id', 'last_modified_log_id');
    }

    public function toSearchableArray()
    {
        return [
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'address' => $this->address,
            'number' => $this->number,
            'email' => $this->email,
            'ticket_type_id' => $this->ticket_type_id,
            'category_id' => $this->category_id,
            'sub_category_id' => $this->sub_category_id,
            'subject' => $this->subject,
            'ref_no' => $this->ref_no,
            'concern' => $this->concern,
            'status' => $this->status,
            'resolved_by' => $this->resolved_by,
            'resolved_date' => $this->resolved_date,
            'closed_date' => $this->closed_date,
        ];
    }
}
