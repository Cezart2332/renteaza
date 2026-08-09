<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(StoreContactRequest $request)
    {
        $validated = $request->validated();
        $validated['is_read'] = false;
        Contact::create($validated);

        return back()->with('message', 'Mesajul tău a fost trimis cu succes!');
    }
}
