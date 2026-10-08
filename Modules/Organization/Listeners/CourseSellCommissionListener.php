<?php

namespace Modules\Organization\Listeners;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Organization\Entities\OrganizationFinance;
use Modules\Organization\Events\CourseSellCommissionEvent;

class CourseSellCommissionListener
{

    public function __construct()
    {
        //
    }


    public function handle(CourseSellCommissionEvent $event)
    {
        try {

            OrganizationFinance::create($event->data);

        }catch (\Exception $exception){
            Toastr::error($exception->getMessage());
        }
    }
}
