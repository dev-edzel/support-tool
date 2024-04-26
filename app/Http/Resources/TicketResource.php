<?php

namespace App\Http\Resources;

use App\Traits\HasHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    use HasHelper;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ticket_info_id' => $this->ticket_info_id,
            'ticket_number' => $this->ticket_number,
            'status' => $this->status ?? 'OPEN',
            'resolved_by' => $this->resolved_by,
            'resolved_date' => $this->resolved_by,
        ];
    }
}
