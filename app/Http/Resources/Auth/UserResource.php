<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $id = Crypt::encrypt($this->id);

        return [
            'id' => $id,
            'username' => $this->username,
            'email' => $this->email,
            'role_id' => $this->role_id,
        ];
    }
}
