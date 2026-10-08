<?php

namespace Modules\Organization\Events;

use Illuminate\Queue\SerializesModels;

class CourseSellCommissionEvent
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $data;

    public function __construct(array $data)
    {
       $this->data = $data;
    }


    public function broadcastOn()
    {
        return [];
    }
}
