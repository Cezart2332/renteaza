<?php

namespace App\Http\Controllers;

use App\Models\OwnerBankAccount;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OwnerBankAccountController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        return Inertia::render('Owner/Payments/BankAccount', [
            'bank' => $user->bankAccount?->only([
                'account_holder_name',
                'iban',
                'bank_name',
                'currency',
                'status',
                'verified_at'
            ]),
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'account_holder_name' => ['required', 'string', 'max:191'],
            'iban'                => ['required', 'string', 'max:34', 'regex:/^([A-Z]{2}\d{2}[A-Z0-9]{1,30})$/'], // simplu; opțional: pachet de validare IBAN
            'bank_name'           => ['nullable', 'string', 'max:191'],
            'currency'            => ['required', 'in:RON,EUR'],
        ], [
            'iban.regex' => 'IBAN invalid.',
        ]);

        OwnerBankAccount::updateOrCreate(
            ['user_id' => $user->id],
            array_merge($data, [
                // când userul modifică, revenim în pending pentru o eventuală verificare
                'status'      => 'pending',
                'verified_at' => null,
                'verified_by' => null,
            ])
        );

        return back()->with('success', 'Contul bancar a fost salvat.');
    }
}
