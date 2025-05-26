<?php

namespace App\Http\Controllers\NCMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CommandController extends Controller
{
    public function index()
    {
      return Inertia::render('ncms/commands/index', [
        'mtitle' => 'Commands to be shown.',
        'commands_list' => [
          [
            'name' => 'Command 1',
            'description' => 'Command 1 description',
            'status' => 'Command 1 status'
          ],
          [
            'name' => 'Command 2',
            'description' => 'Command 2 description',
            'status' => 'Command 2 status'
          ]
        ]
      ]);
    }
}
