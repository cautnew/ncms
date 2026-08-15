<?php

namespace App\Enums;

enum RouteDestinationType: string
{
    case Page = 'page';
    case Asset = 'asset';
    case Redirect = 'redirect';
}
