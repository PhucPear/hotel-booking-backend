<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            'user_id' => $this->user_id,
            'total_price' => $this->total_price,
            'status' => $this->status,
            'created_at' => $this->created_at?->format('Y-m-d:H:i:s'),
            'details' => $this->details->map(function ($item) {
                return [
                    BookingDetailResource::make($item)
                ];
            }),
            // 'payment' => [
            //     'id' => $this->id,
            //     'amount' => $this->payment->amount,
            //     'status' => $this->payment->status,
            //     'paid_at' => $this->payment->paid_at?->format('Y-m-d'),
            //     'transaction_code' => $this->payment->transaction_code,
            // ],
        ];
    }
}
