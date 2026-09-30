<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Імпорт</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; background-color: #f3f4f6; margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card { background: white; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -2px rgba(0,0,0,0.1); width: 100%; max-width: 480px; padding: 32px; box-sizing: border-box; }
        .dropzone { border: 2px dashed #d1d5db; border-radius: 10px; padding: 28px 16px; text-align: center; cursor: pointer; transition: all 0.2s ease; background-color: #f9fafb; display: block; }
        .dropzone:hover { border-color: #3b82f6; background-color: #eff6ff; }
        .btn-choose { display: inline-flex; align-items: center; justify-content: center; gap: 8px; background-color: #e5e7eb; color: #1f2937; padding: 8px 16px; border-radius: 8px; font-weight: 500; font-size: 14px; margin-bottom: 8px; pointer-events: none; }
        .btn-submit { width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; background-color: #2563eb; color: white; border: none; padding: 12px 20px; font-size: 16px; font-weight: 600; border-radius: 8px; cursor: pointer; transition: background-color 0.2s; margin-top: 20px; }
        .btn-submit:hover { background-color: #1d4ed8; }
        .file-info { margin-top: 10px; font-size: 14px; color: #059669; font-weight: 500; display: none; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="card max-w-md w-full bg-white rounded-xl shadow-md p-6 sm:p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center" style="font-size: 24px; font-weight: 700; color: #1f2937; margin-bottom: 24px; text-align: center;">Імпорт бази даних формату XLSX</h1>

        @if (session('success'))
            <div class="mb-4 p-4 rounded-lg bg-green-50 text-green-700 text-sm border border-green-200" style="margin-bottom: 16px; padding: 16px; border-radius: 8px; background-color: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; font-size: 14px;">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-4 rounded-lg bg-red-50 text-red-700 text-sm border border-red-200" style="margin-bottom: 16px; padding: 16px; border-radius: 8px; background-color: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; font-size: 14px;">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('import.process') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label for="import_file" class="dropzone">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true" style="height: 44px; width: 44px; margin: 0 auto 8px auto; color: #9ca3af; display: block;">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>

                    <div>
                        <span class="btn-choose">
                            Обрати файл
                        </span>
                    </div>

                    <p class="text-xs text-gray-500 mt-1" style="font-size: 13px; color: #6b7280; margin-top: 4px;">
                        або перетягніть XLSX-файл сюди
                    </p>

                    <div id="file-name-display" class="file-info">
                        Обрано файл: <span id="file-name-text"></span>
                    </div>
                </label>

                <input
                    type="file"
                    name="import_file"
                    id="import_file"
                    class="sr-only"
                    style="display: none;"
                    required
                    onchange="displayFileName(this)"
                >

                @error('import_file')
                    <p class="mt-2 text-sm text-red-600" style="color: #dc2626; font-size: 14px; margin-top: 8px;">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <button type="submit" class="btn-submit">
                <span>Імпортувати</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                </svg>
            </button>

            <p class="text-xs text-gray-500 text-center mt-3" style="color: #6b7280; font-size: 13px; text-align: center; margin-top: 12px;">
                Файл не зберігається на сервері.
            </p>
        </form>
    </div>

    <script>
        function displayFileName(input) {
            const display = document.getElementById('file-name-display');
            const nameText = document.getElementById('file-name-text');
            if (input.files && input.files[0]) {
                nameText.textContent = input.files[0].name;
                display.style.display = 'block';
            } else {
                display.style.display = 'none';
            }
        }
    </script>
</body>
</html>
