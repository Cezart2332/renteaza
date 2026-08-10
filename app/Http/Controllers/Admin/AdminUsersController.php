<?php

namespace App\Http\Controllers\Admin;

use App\Enums\DocumentStatus;
use App\Enums\DocumentType;
use App\Enums\UserStatus;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class AdminUsersController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->input('filters', []);

        $users = User::query()
            ->when($filters['name'] ?? null, function ($query, $name) {
                $query->where('name', 'like', '%' . $name . '%');
            })
            ->when($filters['email'] ?? null, function ($query, $email) {
                $query->where('email', 'like', '%' . $email . '%');
            })
            ->when($filters['email_verified_at'] ?? null, function ($query, $date) {
                $query->whereDate('email_verified_at', $date);
            })
            ->when($filters['status'] ?? null, function ($query, $status) {
                $query->where('status', $status);
            })
            ->paginate(10);

        return Inertia::render('Admin/Users/List', [
            'users' => $users,
            'prevFilters' => $filters,
            'userStatusOptions' => UserStatus::options(),
        ]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return Inertia::render('Admin/Users/Edit', [
            'user' => $user,
        ]);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validated();
        $user->update($data);

        return redirect()->back()->with('message', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('message', 'User deleted successfully.');
    }

    public function showPersonalUserDocuments($id)
    {
        $user = User::findOrFail($id);

        $allowedTypes = DocumentType::personalDocumentTypes();

        $existing = $user->documents()->get();

        $documents = $existing->map(fn($doc) => [
            'type' => $doc->type,
            'url' => route('documents.view', ['path' => $doc->path]),
            'admin_comment' => $doc->admin_comment,
            'verified_at' => $doc->verified_at ? $doc->verified_at->format('Y-m-d') : null,
        ]);

        $existingTypes = $existing->pluck('type')->unique()->toArray();

        $missingTypes = array_values(
            array_diff($allowedTypes, $existingTypes)
        );

        return Inertia::render('Admin/Users/UserDocuments', [
            'user' => $user,
            'documents' => $documents,
            'missingTypes' => $missingTypes,
            'allowedTypes' => $allowedTypes,
        ]);
    }

    public function showVehicleUserDocuments($id, $vehicleId)
    {
        $owner = User::findOrFail($id);
        $vehicle = $owner->vehicles()->findOrFail($vehicleId);

        $allowedTypes = DocumentType::carDocumentTypes();

        $existing = $vehicle->documents()->get();

        $documents = $existing->map(fn($doc) => [
            'type' => $doc->type,
            'url' => route('vehicle-documents.view', ['path' => $doc->path]),
            'admin_comment' => $doc->admin_comment,
            'verified_at' => $doc->verified_at ? $doc->verified_at->format('Y-m-d') : null,
        ]);

        $existingTypes = $existing->pluck('type')->unique()->toArray();

        $missingTypes = array_values(
            array_diff($allowedTypes, $existingTypes)
        );


        return Inertia::render('Admin/Users/Vehicles/VehicleDocuments', [
            'vehicle' => $vehicle,
            'documents' => $documents,
            'missingTypes' => $missingTypes,
            'allowedTypes' => $allowedTypes,
        ]);
    }

    public function showUserVehicles($id)
    {
        $user = User::findOrFail($id);

        $vehicles = $user->vehicles()->with(['documents', 'owner'])->get();

        return Inertia::render('Admin/Users/Vehicles/List', [
            'user' => $user,
            'vehicles' => $vehicles,
        ]);
    }

    public function storeComments(Request $request, User $user)
    {
        foreach ($request->comments as $type => $comment) {
            $doc = $user->documents()->where('type', $type)->first();
            if ($doc && !$doc->admin_comment) {
                $doc->update([
                    'admin_comment' => $comment,
                    'verified_at' => now(),
                ]);
            }
        }

        return back()->with('message', 'Feedback trimis cu succes.');
    }

    public function storeCommentsForVehiclesDocuments(Request $request, Vehicle $vehicle)
    {
        foreach ($request->comments as $type => $comment) {
            $doc = $vehicle->documents()->where('type', $type)->first();
            if ($doc && !$doc->admin_comment) {
                $doc->update([
                    'admin_comment' => $comment,
                    'verified_at' => now(),
                ]);
            }
        }

        return back()->with('message', 'Feedback trimis cu succes.');
    }
    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $status = $request->status;

        if (in_array($status, UserStatus::values())) {
            $user->update(['status' => $status]);
            return redirect()->back()->with('message', 'Status actualizat.');
        }

        return redirect()->back()->withErrors(['status' => 'Status invalid.']);
    }

    public function updateStatusVehicle(Request $request, Vehicle $vehicle)
    {
        $status = $request->status;

        if (in_array($status, DocumentStatus::values())) {
            // is_verified se seta 'true' si la respingere, deci o masina refuzata
            // ramanea marcata ca verificata. Acum urmeaza statusul, intr-un
            // singur UPDATE.
            $vehicle->update([
                'status' => $status,
                'is_verified' => $status === DocumentStatus::ACTIVE->value,
            ]);

            return redirect()->back()->with('message', 'Status actualizat.');
        }

        return redirect()->back()->withErrors(['status' => 'Status invalid.']);
    }

}
