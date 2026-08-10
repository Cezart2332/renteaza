<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        // Aceleasi props ca la Auth/Login: cele doua pagini randeaza acelasi
        // panou combinat, doar cu tabul implicit diferit.
        return Inertia::render('Auth/Register', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            // doar rolurile pe care si le poate alege singur cineva la inregistrare
            'account_type' => ['required', Rule::in(['user', 'company-owner'])],
        ], [
            'account_type.required' => 'Alege ce vrei să faci pe platformă.',
            'account_type.in' => 'Tipul de cont selectat nu este valid.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Rolul 'user' e zona principala a aplicatiei (routes/user.php): masini,
        // rezervari, contracte, plati, calendar. Il primeste toata lumea, altfel
        // contul e inutilizabil — middleware-ul 'role:...' da 403 pe tot.
        //
        // 'company-owner' NU inlocuieste 'user': routes/companyOwner.php are doar
        // doua rute, editarea mini-site-ului public al firmei. Cine listeaza
        // masini are nevoie de ambele.
        $roles = ['user'];

        if ($request->account_type === 'company-owner') {
            $roles[] = 'company-owner';
        }

        $roleIds = collect($roles)
            ->map(fn (string $name) => Role::query()->firstOrCreate(['name' => $name])->id)
            ->all();

        $user->roles()->syncWithoutDetaching($roleIds);

        // CompanyOwnerController::editProfile face Company::where(...)->firstOrFail(),
        // deci un proprietar fara rand in `companies` primeste 404 imediat dupa
        // inregistrare. Ii cream profilul de firma gol, urmeaza sa il completeze.
        if ($request->account_type === 'company-owner') {
            $company = new Company([
                'name' => $request->name,
                'email' => $request->email,
            ]);
            // user_id nu e in $fillable pe model, deci se seteaza explicit
            $company->user_id = $user->id;
            $company->save();
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect()->intended($this->homeRouteFor($user));
    }

    /**
     * Unde trimitem utilizatorul dupa inregistrare, in functie de rol.
     * Aceleasi destinatii ca la login (AuthenticatedSessionController::store).
     */
    private function homeRouteFor(User $user): string
    {
        // aceeasi ordine ca la login: 'user' inaintea lui 'company-owner',
        // pentru ca acolo e aplicatia propriu-zisa
        if ($user->hasRole('user')) {
            return route('user.profile.show', absolute: false);
        }

        if ($user->hasRole('company-owner')) {
            return route('company-owner.profile.edit', absolute: false);
        }

        return '/';
    }
}
