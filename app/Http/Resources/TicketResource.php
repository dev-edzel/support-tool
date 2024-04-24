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
        $ticket_type = new TicketTypeResource(
            $this->whenLoaded('ticket_type')
        );
        $category = new CategoryResource(
            $this->whenLoaded('category')
        );
        $sub_category = new SubCategoryResource(
            $this->whenLoaded('sub_category')
        );

        $data = array_merge(
            $this->relatedRss($ticket_type, 'ticket_type_id', 'ticket_type'),
            $this->relatedRss($category, 'category_id', 'category'),
            $this->relatedRss($sub_category, 'sub_category_id', 'sub_category')
        );

        return [
            'id' => $this->id,
            'ticket_number' => $this->ticket_number,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'address' => $this->address,
            'number' => $this->number,
            'email' => $this->email,
            ...$data,
            'subject' => $this->subject,
            'ref_no' => $this->ref_no,
            'concern' => $this->concern,
            'status' => $this->status ?? 'OPEN',
            // 'resolved_by' => $this->resolved_by,
            // 'resolved_date' => $this->resolved_by,
            // 'closed_date' => $this->resolved_by,
            // 'last_modified_log_id' => $this->last_modified_log_id,
        ];
    }
}
