<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

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
        'status',
        'resolved_by',
        'resolved_date',
        'closed_date'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function ticket_type(): HasOne
    {
        return $this->hasOne(TicketType::class);
    }

    public function category(): HasOne
    {
        return $this->hasOne(Category::class);
    }

    public function sub_category(): HasOne
    {
        return $this->hasOne(Category::class);
    }
}
