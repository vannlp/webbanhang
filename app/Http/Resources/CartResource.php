<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $cartArray = [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'price' => number_format($this->price),
            'handled_price' => number_format($this->handled_price),
            'type' => $this->type,
            'session' => $this->session,
        ];

        if(!empty($this->cartDetails)) {
            $arrayCartDetail = [];

            foreach($this->cartDetails as $cartDetail) {
                $arrayCartDetail[] = [
                    'id' => $cartDetail->id,
                    'cart_id' => $cartDetail->cart_id,
                    'product_id' => $cartDetail->product_id,
                    'price' => number_format($cartDetail->price),
                    'handled_price' => number_format($cartDetail->handled_price),
                    'quantity' => $cartDetail->quantity,
                    'product_name' => $cartDetail->product->name,
                    'product_avatar_link' => $cartDetail->product->avatar->url
                ];
            }

            $cartArray['cartDetails'] = $arrayCartDetail;
        }

        return $cartArray;
    }
}
