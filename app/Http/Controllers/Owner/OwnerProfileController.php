<?php

namespace App\Http\Controllers\Owner;

use App\Enums\DocumentType;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class OwnerProfileController extends Controller
{
    public function show(Request $request)
    {
        $search = $request->input('search');
        $order = $request->input('order', 'desc');

        $user = Auth()->user();
        $allowedTypes = DocumentType::personalDocumentTypes();
        $totalDocuments = count($allowedTypes);

        $query = $user
            ->documents()
            ->whereIn('type', $allowedTypes);

        if ($search) {
            $query->where('type', 'like', "%{$search}%");
        }

        $now = Carbon::now();
        $vehicles_nr = $user->vehicles()->count();
        $bookings_nr = $user->bookingsOwner()->count();
        $active_rentals = $user->bookingsOwner()->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)->count();

        $documents = $query
            ->orderBy('expires_at', $order)
            ->paginate(5)
            ->withQueryString()
            ->through(fn($doc) => [
                'type' => $doc->type,
                'url' => route('documents.view', ['path' => $doc->path]),
                'verified_at' => $doc->verified_at ? $doc->verified_at->format('Y-m-d') : null,
                'expires_at' => $doc->expires_at ? $doc->expires_at->format('Y-m-d') : null,
                'status' => $doc->status,
                'admin_comment' => $doc->admin_comment,
            ]);

        return Inertia::render('Owner/Profile/Show', [
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'created_at' => $user->created_at->format('Y-m-d'),
                'status' => $user->status,
                'profile_picture' => $user->documents()
                    ->where('type', DocumentType::ProfilePhoto)
                    ->first()?->path ? route('documents.view', [
                        'path' => $user->documents()
                            ->where('type', DocumentType::ProfilePhoto)
                            ->first()->path
                    ]) : null,

            ],
            'documents' => $documents,
            'totalDocuments' => $totalDocuments,
            'allowedTypes' => $allowedTypes,
            'bookings_nr' => $bookings_nr,
            'vehicles_nr' => $vehicles_nr,
            'active_rentals' => $active_rentals
        ]);
    }

    public function edit()
    {
        $user = Auth()->user();

        return Inertia::render('Owner/Profile/Edit', [
            'user' => $user,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'phone' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];

        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }

        $user->save();

        return redirect()->route('user.profile.show')->with('success', 'Profile updated.');
    }
    public function uploadDocuments(Request $request)
    {
        $user = auth()->user();
        $allowedTypes = $request->input('allowedTypes', []);

        foreach ($allowedTypes as $type) {
            if ($request->hasFile($type)) {
                $file = $request->file($type);
                $extension = $file->getClientOriginalExtension();
                $filename = "document_{$user->id}_{$type}." . $extension;
                $path = "documents/{$user->id}/{$filename}";

                // Șterge vechiul document dacă există
                $existing = $user->documents()->where('type', $type)->first();
                if ($existing && Storage::disk('aws-private')->exists($existing->path)) {
                    Storage::disk('aws-private')->delete($existing->path);
                }

                // Salvează noul fișier
                Storage::disk('aws-private')->put($path, file_get_contents($file));

                if ($existing) {
                    $existing->update(['path' => $path]);
                } else {
                    $user->documents()->create([
                        'id' => Uuid::uuid(),
                        'type' => $type,
                        'path' => $path,
                    ]);
                }
            }
        }

        return redirect()->back()->with('message', 'Documents uploaded.');
    }

    public function editDocuments()
    {
        $user = auth()->user();

        $allowedTypes = DocumentType::personalDocumentTypes();

        $existing = $user->documents()
            ->whereIn('type', $allowedTypes)
            ->get();

        $documents = $existing->map(fn($doc) => [
            'type' => $doc->type,
            'url' => route('documents.view', ['path' => $doc->path]),
        ]);

        $existingTypes = $existing->pluck('type')->unique()->toArray();

        // calculăm tipurile lipsă
        $missingTypes = array_values(
            array_diff($allowedTypes, $existingTypes)
        );


        return Inertia::render('Owner/Profile/UploadDocuments', [
            'documents' => $documents,
            'missingTypes' => $missingTypes,
            'allowedTypes' => $allowedTypes,
        ]);
    }
}
