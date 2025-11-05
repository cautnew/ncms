<?php

namespace App\Http\Controllers\Tests;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Env;
use PHTML\Core\TAG;
use PHTML\Templates\HTML5;

class ChegadaTaise extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $html = new HTML5();
        $html->setPageTitle('Contador para a Chegada de Taíse');
        $html->appendToHead(TAG::meta(charset: 'utf-8'));
        $html->appendToHead(TAG::meta('viewport', content: 'width=device-width, initial-scale=1'));
        $html->appendToHead(TAG::link('https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css', 'stylesheet', integrity: 'sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB', crossorigin: 'anonymous'));

        $welcomeSection = TAG::section('p-4 text-center', append: [
            TAG::h1(html: 'Vem, Meu Amor!!! ❤️'),
            TAG::h3(html: 'Vem ficar comigo, minha vida!!! 🥰😍')
        ]);

        $counterSection = TAG::section('p-4 text-center fs-1 fw-bold', append: [
            TAG::p(html: 'Restam'),
            TAG::p(append: [TAG::span(id: 'time-left-days')]),
            TAG::p(append: [TAG::span(id: 'time-left-hours')]),
            TAG::p(append: [TAG::span(id: 'time-left-minutes')]),
            TAG::p(append: [TAG::span(id: 'time-left-seconds')])
        ]);

        $html->appendToBody($welcomeSection);
        $html->appendToBody($counterSection);

        $html->appendToBody(TAG::script(html: <<<JAVASCRIPT
        const timeGoal = new Date("November 15, 2025 04:30:00");
        const tagTimeLeftDays = document.querySelector('#time-left-days');
        const tagTimeLeftHours = document.querySelector('#time-left-hours');
        const tagTimeLeftMinutes = document.querySelector('#time-left-minutes');
        const tagTimeLeftSeconds = document.querySelector('#time-left-seconds');
        
        let currentFrase = 0;
        let loops = 0;

        const convertAmountOfTime = (number, indicator) => {
            if (number > 1) {
                return number + ' ' + indicator + 's';
            } else if (number === 1) {
                return '1 ' + indicator;
            }

            return '';
        };

        const funcTimeCounting = () => {
            const currentTime = new Date();
            const timeDiffSeconds = parseInt((timeGoal - currentTime) / 1000);
            const timeDiffMinutes = timeDiffSeconds / 60;
            const timeDiffHours = timeDiffMinutes / 60;
            const timeDiffDays = timeDiffHours / 24;

            const timeDiffDaysInt = parseInt(timeDiffDays);
            const timeDiffHoursInt = parseInt(timeDiffHours - 24 * timeDiffDaysInt);
            const timeDiffMinutesInt = parseInt(timeDiffMinutes - 60 * parseInt(timeDiffHours));
            const timeDiffSecondsInt = parseInt(timeDiffSeconds - 60 * parseInt(timeDiffMinutes));

            tagTimeLeftDays.innerHTML = convertAmountOfTime(timeDiffDaysInt, 'dia');
            tagTimeLeftHours.innerHTML = convertAmountOfTime(timeDiffHoursInt, 'hora');
            tagTimeLeftMinutes.innerHTML = convertAmountOfTime(timeDiffMinutesInt, 'minuto');
            tagTimeLeftSeconds.innerHTML = convertAmountOfTime(timeDiffSecondsInt, 'segundo');
        };

        const timeCounting = setInterval(() => {
            funcTimeCounting();
        }, 1000);

        funcTimeCounting();
        JAVASCRIPT));
        $html->appendToBody(TAG::script('https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js', integrity: 'sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI', crossorigin: 'anonymous'));

        return $html;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
