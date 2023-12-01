<?php

namespace App\Http\Resources;

use App\Models\CartItem;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        
        $productsId = OrderItem::whereRelation('order', 'user_id', Auth::id())->pluck('product_id');

        return [
            'id' => $this->id,
            'products' => $productsId
        ];

    }
}
