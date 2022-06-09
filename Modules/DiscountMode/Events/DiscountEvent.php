<?php

namespace Modules\DiscountMode\Events;

use App\Discount;
use Illuminate\Queue\SerializesModels;

class DiscountEvent
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $discount, $user_id ;
    public function __construct(Discount $discount, $user_id)
    {
        // dd($discount, $user_id);
        $this->discount = $discount ;
        $this->user_id = $user_id ;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
