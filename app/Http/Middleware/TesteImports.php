<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use PHTML\TAG;
use Symfony\Component\HttpFoundation\Response;

class TesteImports
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $html = TAG::html('pt-br');
        $html->append([
            $head = TAG::head(),
            $body = TAG::body()
        ]);

        $head->append(TAG::link('https://vai.com'));
        $head->append($title = TAG::tagTitle(''));
        View::share('renderer', new \App\Services\Teste);
        View::share('TAG', new TAG());
        View::share('body', $body);
        View::share('pageTitle', $title);
        View::share('head', $head);
        View::share('html', $html);

        return $next($request);
    }
}
