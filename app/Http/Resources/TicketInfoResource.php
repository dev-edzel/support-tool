<?php

namespace App\Http\Resources;

use App\Traits\HasHelper;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketInfoResource extends JsonResource
{
    use HasHelper;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ticket_number' => $this->ticket->ticket_number,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'address' => $this->address,
            'number' => $this->number,
            'email' => $this->email,
            'ticket_type' => $this->whenLoaded('ticket_type')
                ? $this->ticket_type->name : null,
            'category' =>  $this->whenLoaded('category')
                ? $this->category->type : null,
            'sub_category' => $this->whenLoaded('sub_category')
                ? $this->sub_category->type : null,
            'subject' => $this->subject,
            'ref_no' => $this->ref_no,
            'concern' => $this->concern,
            'image' => $this->image,
            'status' => $this->ticket->status,
            'assigned_to' => $this->ticket->assigned_to
        ];
    }
}
