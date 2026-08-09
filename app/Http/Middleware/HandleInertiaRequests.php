<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    public function rootView(Request $request)
    {
        $name = $request->route()?->getName() ?? '';

        if (str_starts_with($name, 'user.') || str_starts_with($name, 'admin.')) {
            return 'app';
        }

        return 'public';
    }

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'user' => Auth::user() ? [
                'id' => Auth::id(),
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'roles' => Auth::user()->roles->toArray(),
                'status' => Auth::user()->status,
            ] : null,
            'message' => fn() => $request->session()->get('message'),
            'success' => fn() => $request->session()->get('success'),
            'level_up' => fn() => $request->session()->get('level_up'),
            'errorMessage' => fn() => $request->session()->get('errorMessage'),
            'selectedRole' => fn() => session('dashboard_role', 'Client'),
        ]);
    }
}
