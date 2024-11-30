<?php

namespace App\Enums\ViewPaths\Admin;

enum AbandonedCart
{
    const LIST = [
        URI => 'list',
        VIEW => 'admin-views.abandoned-cart.view'
    ];

    const DELETE = [
        URI => 'delete',
        VIEW => ''
    ];

    const STATUS = [
        URI => 'status',
        VIEW => ''
    ];

}
