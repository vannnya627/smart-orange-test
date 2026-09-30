<?php

declare(strict_types=1);

namespace App\Http\Controllers;

final class ShowImportFormController extends Controller
{
    public function __invoke()
    {
        return view('import.form');
    }
}
