<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Action\ImportLeadAction;
use App\Http\Requests\ImportLeadRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

final class ImportLeadController extends Controller
{
    public function __construct(
        private readonly ImportLeadAction $action,
    ) {}

    public function __invoke(ImportLeadRequest $request): RedirectResponse
    {
        $startTime = hrtime(true);

        $file = $request->getFile();

        $savedPath = $file->store('imports', 'local');
        if ($savedPath === false) {
            throw new RuntimeException('Failed to store file');
        }

        $fullPath = Storage::disk('local')->path($savedPath);

        $this->action->run($fullPath);

        Storage::disk('local')->delete($savedPath);

        $executionTime = (hrtime(true) - $startTime) / 1e+9;

        return redirect()->back()->with('success', "Успішно імпортовано базу за $executionTime секунд. ");
    }
}
