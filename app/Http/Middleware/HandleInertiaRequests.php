<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'app' => [
                'locale' => $request->getLocale(),
                'name' => config('app.name'),
            ],
            'auth' => fn () => $this->authenticatedAccount(),
        ];
    }

    private function authenticatedAccount(): ?array
    {
        foreach (['client', 'staff'] as $guard) {
            if ($account = Auth::guard($guard)->user()) {
                $sharedAccount = [
                    'type' => $guard,
                    'first_name' => $account->first_name,
                    'last_name' => $account->last_name,
                ];

                if ($account instanceof User) {
                    $sharedAccount['roles'] = $account->getRoleNames()->values()->all();
                    $sharedAccount['permissions'] = $account->getAllPermissions()
                        ->pluck('name')
                        ->values()
                        ->all();
                }

                return $sharedAccount;
            }
        }

        return null;
    }
}
