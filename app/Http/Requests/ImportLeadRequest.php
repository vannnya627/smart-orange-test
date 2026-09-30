<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use RuntimeException;

final class ImportLeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'import_file' => 'required|file|mimes:xlsx',
        ];
    }

    public function getFile(): UploadedFile
    {
        $file = $this->file('import_file');

        if (! $file instanceof UploadedFile) {
            throw new RuntimeException('File not found or invalid');
        }

        return $file;
    }
}
