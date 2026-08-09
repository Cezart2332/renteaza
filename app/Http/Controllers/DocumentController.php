<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function view($path)
    {
        $user = Auth::user();

        // Extrage user_id din path-ul "documents/{user_id}/{filename}"
        if (!preg_match('#^documents/(\d+)/.+$#', $path, $matches)) {
            abort(403, 'Format path invalid.');
        }

        $ownerId = (int) $matches[1];

        // Verifică dacă userul este admin sau deține fișierul
        $isOwner = $user->id === $ownerId;
        $isAdmin = $user->hasRole('admin');

        if (!$isOwner && !$isAdmin) {
            abort(403, 'Nu ai permisiunea să accesezi acest fișier.');
        }

        if (!Storage::disk('aws-private')->exists($path)) {
            abort(404, 'Fișierul nu a fost găsit.');
        }

        $stream = Storage::disk('aws-private')->readStream($path);
        $mimeType = Storage::disk('aws-private')->mimeType($path);

        return response()->stream(function () use ($stream) {
            fpassthru($stream);
        }, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
        ]);
    }

    public function viewDocumentsVehicles($path)
    {
        $user = Auth::user();

        // 1) Extrage owner_id din "documents_vehicles/owner_{id}/..."
        if (!preg_match('#^documents_vehicles/owner_(\d+)/.+$#', $path, $m)) {
            abort(403, 'Format path invalid.');
        }
        $ownerId = (int) $m[1];

        // 2) Doar owner sau admin
        $isOwner = $user->id === $ownerId;
        $isAdmin = $user->hasRole('admin');
        if (!$isOwner && !$isAdmin) {
            abort(403, 'Nu ai permisiunea să accesezi acest fișier.');
        }

        if (!Storage::disk('aws-private')->exists($path)) {
            abort(404, 'Fișierul nu a fost găsit.');
        }

        $stream = Storage::disk('aws-private')->readStream($path);
        $mimeType = Storage::disk('aws-private')->mimeType($path);

        return response()->stream(function () use ($stream) {
            fpassthru($stream);
        }, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
        ]);
    }
}
