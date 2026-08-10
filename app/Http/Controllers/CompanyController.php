<?php

namespace App\Http\Controllers;

use App\Models\Company;

class CompanyController extends Controller
{
    public function show($companyId)
    {
        $company = Company::with('vehicles')->findOrFail($companyId);

        return inertia('Companies/Show', [
            'company' => $company,
        ]);
    }
}
