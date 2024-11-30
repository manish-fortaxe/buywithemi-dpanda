<?php

namespace App\Enums\ViewPaths\Admin;

enum Blog
{
    const LIST = [
        URI => 'list',
        VIEW => 'admin-views.blog.view'
    ];

    const ADD = [
        URI => 'add',
        VIEW => ''
    ];

    const DELETE = [
        URI => 'delete',
        VIEW => ''
    ];

    const STATUS = [
        URI => 'status',
        VIEW => ''
    ];

    const UPDATE = [
        URI => 'update',
        VIEW => 'admin-views.blog.edit',
        ROUTE => 'admin.blog.list'
    ];
}
