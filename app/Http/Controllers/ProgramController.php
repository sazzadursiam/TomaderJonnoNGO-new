<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Contracts\View\View;

class ProgramController extends Controller
{
    public function index(): View
    {
        return view('programs.index', [
            'programs' => Program::query()->published()->get(),
        ]);
    }

    public function show(Program $program): View
    {
        abort_unless($program->is_published, 404);

        return view('programs.show', [
            'program' => $program,
        ]);
    }
}
