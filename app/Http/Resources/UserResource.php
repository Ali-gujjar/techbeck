<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'user_image' => $this->user_image,
            'email' => $this->email,
            'country_code' => $this->country_code,
            'phone_number' => $this->phone_number,
            'company_name' => $this->company_name,
            'passport_image' => $this->passport_image,
            'emirates_image' => $this->emirates_image,
            'trading_license_image' => $this->trading_license_image,
            'country' => $this->country,
            'role' => $this->role,
            'type' => $this->type,
            'user_number' => $this->user_number,
        ];
    }
}
