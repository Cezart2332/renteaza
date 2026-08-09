<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CompanyController extends Controller
{
    public function show($companyId)
    {
        $company = Company::with('vehicles')->findOrFail($companyId);

        return inertia('Companies/Show', [
            'company' => $company,
        ]);
    }

    public function edit()
    {
        $company = Company::where('user_id', auth()->id())->firstOrFail();
 
        return Inertia::render('CompanyProfile/CompanyProfileForm', ['company' => $company]);
    }
}
