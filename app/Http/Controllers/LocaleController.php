<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function __invoke(Request $request, string $locale)
    {
        abort_unless(in_array($locale, ['en', 'ar'], true), 404);
        $request->session()->put('locale', $locale);
        $path = $request->input('return', '/');
        // Only redirect to local paths, including their query strings.
        if (! is_string($path) || ! str_starts_with($path, '/') || str_starts_with($path, '//') || preg_match('/[\\\\\x00-\x1F\x7F]/', $path)) {
            $path = '/';
        }

        return redirect($path);
    }
}
