<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switch(string $locale): RedirectResponse
    {
        abort_unless(array_key_exists($locale, config('locales.supported')), 404);

        session(['locale' => $locale]);

        return redirect()->back();
    }
}
