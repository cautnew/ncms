<?php

namespace App\Enums;

enum RequestMethods: int
{
    case GET = 1;
    case POST = 2;
    case PUT = 3;
    case PATCH = 4;
    case DELETE = 5;
    case OPTIONS = 6;
    case HEAD = 7;
    case TRACE = 8;
}
