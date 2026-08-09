<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CompanyOwnerController extends Controller
{
    public function editProfile()
    {
        $company = Company::where('user_id', auth()->id())->firstOrFail();
        return Inertia::render('CompanyOwner/Profile/Edit', [
            'company' => $company
        ]);
    }

public function updateProfile(UpdateCompanyRequest $request, $companyId)
{
    $validated = $request->validated();
    
    $company = Company::findOrFail($companyId);
    $data = collect($validated)->only([
        'name','description','website','email','phone','address','latitude','longitude'
    ])->toArray();

    // === LOGO === (exact ca la tine)
    $removeLogo = $request->boolean('remove_logo');
    $newLogo    = $request->file('logo');

    if ($removeLogo && !$newLogo && $company->logo) {
        Storage::disk('aws-public')->delete($company->logo);
        $data['logo'] = null;
    }
    if ($newLogo) {
        if ($company->logo) {
            Storage::disk('aws-public')->delete($company->logo);
        }
        $data['logo'] = $newLogo->store("companies/company_{$company->id}/logo", 'aws-public');
    }

    // === GALERIE ===
    $existing = $company->gallery_images ?? [];

    // 1) Remove (șterge fizic + scoate din listă)
    $toRemove = (array) $request->input('images_remove', []);
    if (!empty($toRemove)) {
        foreach ($toRemove as $path) {
            Storage::disk('aws-public')->delete($path);
        }
        $existing = array_values(array_diff($existing, $toRemove));
    }

    // 2) Upload pentru imaginile noi (în ORDINEA din request)
    $newPaths = [];
    if ($request->hasFile('images_new')) {
        foreach ($request->file('images_new') as $file) {
            $newPaths[] = $file->store("companies/company_{$company->id}/gallery", 'aws-public');
        }
    }

    // 3) Reconstruiește ordinea finală
    $gallery = [];

    if ($request->filled('images_order_full')) {
        // ex: ["companies/.../a.jpg", "__new__:0", "companies/.../b.jpg", "__new__:1"]
        $orderFull = $request->input('images_order_full', []);

        foreach ($orderFull as $item) {
            if (is_string($item) && str_starts_with($item, '__new__:')) {
                $idx = (int) Str::after($item, '__new__:'); 
                if (isset($newPaths[$idx])) {
                    $gallery[] = $newPaths[$idx];
                }
            } else {
                // item = path existent
                if (in_array($item, $existing, true)) {
                    $gallery[] = $item;
                }
            }
        }

        // fallback: adaugă ce a rămas pe dinafară, dar NU dubla
        foreach ($existing as $p) {
            if (!in_array($p, $gallery, true)) {
                $gallery[] = $p;
            }
        }
        foreach ($newPaths as $p) {
            if (!in_array($p, $gallery, true)) {
                $gallery[] = $p;
            }
        }
    } else {
        // compat: dacă nu ai `images_order_full`, păstrează vechiul comportament:
        // reordonează DOAR existentele după `images_order` și apoi lipește noile la coadă
        $ordered = [];
        if ($request->filled('images_order')) {
            $order = (array) $request->input('images_order');
            foreach ($order as $p) {
                if (in_array($p, $existing, true)) {
                    $ordered[] = $p;
                }
            }
        }
        // restul existentelor, în ordinea veche
        foreach ($existing as $p) {
            if (!in_array($p, $ordered, true)) {
                $ordered[] = $p;
            }
        }
        // noile fișiere după
        $gallery = array_merge($ordered, $newPaths);
    }

    $data['gallery_images'] = $gallery;

    // === SAVE ===
    $company->update($data);

    return redirect()
        ->route('company-owner.profile.edit', $company)
        ->with('success', 'Profilul a fost actualizat cu succes.');
}

}
