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
        'subject',
        'type',
        'category',
        'sub_category',
        'ref_no',
        'concern',
        'status',
        'resolved_by'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function client_info(): HasOne
    {
        return $this->hasOne(ClientInfo::class);
    }
}
