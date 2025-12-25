<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        if ($request->user()->hasVerifiedEmail()) {
            $role = $request->user()->role;
            if ($role === 'admin') {
                return redirect()->intended('/dashboard');
            } elseif ($role === 'staff') {
                return redirect()->intended('/agenda');
            }
            return redirect()->intended('/reports');
        }
        return view('auth.verify-email');
    }
}
