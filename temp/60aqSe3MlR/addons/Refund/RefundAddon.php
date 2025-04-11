<?php

namespace App\Addons\Refund;

use App\LaravelAddons\Addon;

class RefundAddon extends Addon
{
    public $name = 'Refund';

    public function boot()
    {
        $this->enableViews();
    }
}
