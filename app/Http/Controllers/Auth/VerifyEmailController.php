<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            $role = $request->user()->role;
            $url = '/reports';

            if ($role === 'admin') {
                $url = '/dashboard';
            } elseif ($role === 'staff') {
                $url = '/agenda';
            }

            return redirect()->intended($url . '?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        $role = $request->user()->role;
        $url = '/reports';

        if ($role === 'admin') {
            $url = '/dashboard';
        } elseif ($role === 'staff') {
            $url = '/agenda';
        }

        return redirect()->intended($url . '?verified=1');
    }
}
