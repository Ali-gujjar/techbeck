<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SignalResource extends JsonResource
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
            'user_name' => $this->user_name,
            'instrument' => $this->instrument,
            'order_type_id' => $this->order_type_id,
            'order_type' => $this->whenLoaded('orderType', function () {
                return $this->orderType->name;
            }),
            'price' => $this->price,
            'sl' => $this->sl,
            'tp1' => $this->tp1,
            'tp2' => $this->tp2,
            'tp3' => $this->tp3,
            'date_time' => $this->date_time,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'image' => $this->image,
            'trading_chart_link' => $this->trading_chart_link,
            'screenshot' => $this->screenshot,
            'result_type' => $this->result_type,
            'signal_amount' => $this->signal_amount,
        ];
    }
}
