<?php

namespace App\Http\Controllers\Owner;

use App\Enums\CarType;
use App\Enums\DocumentType;
use App\Http\Controllers\Controller;
use App\Http\Requests\OwnerCarStoreRequest;
use App\Http\Requests\OwnerCarUpdateRequest;
use App\Models\FuelType;
use App\Models\Location;
use App\Models\RentalType;
use App\Models\Transmission;
use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Services\CarService;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;

class OwnerCarController extends Controller
{
    public function __construct(public CarService $carService)
    {
    }

    public function index(Request $request)
    {
        $owner = Auth::user();

        $search = trim((string) $request->query('search', ''));
        $statuses = array_filter((array) $request->query('statuses', []));
        $fuels = array_filter((array) $request->query('fuels', []));
        $brands = array_filter((array) $request->query('brands', []));
        $verified = filter_var($request->query('verified', false), FILTER_VALIDATE_BOOL);

        $brandOptions = $owner->vehicles()
            ->selectRaw('LOWER(TRIM(brand)) as value, MIN(brand) as label, COUNT(*) as count')
            ->groupBy('value')
            ->orderBy('label')
            ->get();

        $cars = $owner->vehicles()
            ->with(['fuelType:id,name'])
            ->when($search !== '', function ($q) use ($search) {
                $q->where(
                    fn($qq) =>
                    $qq->where('brand', 'like', "%{$search}%")
                        ->orWhere('model', 'like', "%{$search}%")
                );
            })
            ->when(!empty($statuses), fn($q) => $q->whereIn('status', $statuses))
            ->when(!empty($brands), function ($q) use ($brands) {
                $brandsLower = array_map('mb_strtolower', $brands);
                $q->whereIn(DB::raw('LOWER(brand)'), $brandsLower);
            })
            ->when(!empty($fuels), function ($q) use ($fuels) {
                $q->whereHas('fuelType', function ($qq) use ($fuels) {
                    $qq->whereIn(DB::raw('LOWER(name)'), $fuels);
                });
            })
            ->when($verified, fn($q) => $q->where('is_verified', true))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString()
            ->through(fn($v) => [
                'id' => $v->id,
                'brand' => $v->brand,
                'model' => $v->model,
                'price_per_day' => $v->price_per_day,
                'status' => $v->status,
                'fuel' => $v->fuelType?->slug,
                'is_verified' => (bool) $v->is_verified,
                'slug' => $v->slug,
            ]);

        return Inertia::render('Owner/Cars/Index', [
            'cars' => $cars,
            'facets' => ['brands' => $brandOptions],
            'prev' => [
                'search' => $search,
                'statuses' => $statuses,
                'fuels' => $fuels,
                'brands' => $brands,
                'verified' => $verified,
            ],
        ]);
    }
    public function create()
    {
        $ownerId = Auth::id();
        $locations = Location::query()
            ->whereHas('vehicles', function ($q) use ($ownerId) {
                $q->where('owner_id', $ownerId);
            })
            ->distinct()
            ->orderBy('locations.name')
            ->get();

        return Inertia::render('Owner/Cars/Create', [
            'fuelTypes' => FuelType::select('id', 'name')->orderBy('name')->get(),
            'transmissions' => Transmission::select('id', 'name')->orderBy('name')->get(),
            'vehicleTypes' => VehicleType::select('id', 'name')->orderBy('name')->get(),
            'rentalTypes' => RentalType::select('id', 'label')->orderBy('label')->get(),
            'locations' => $locations,
            'carTypes' => collect(CarType::cases())->map(fn($c) => ['value' => $c->value, 'label' => ucfirst($c->value)]),
        ]);
    }

    public function store(OwnerCarStoreRequest $request)
    {
        $owner = Auth::user();
        $data = $request->validated();
        $disk = Storage::disk('aws-public');

        return DB::transaction(function () use ($data, $owner, $disk) {

            $vehicleId = (string) Str::uuid();
            $basePath = "vehicles/owner_{$owner->id}/vehicle_{$vehicleId}";
            // 1) Upload COVER (dacă e)
            $coverPath = null;
            if (!empty($data['cover_image'])) {
                $file = $data['cover_image'];
                $ext = $file->getClientOriginalExtension() ?: 'jpg';
                $filename = 'cover_' . Str::uuid() . '.' . $ext;
                // salvăm DOAR calea relativă (nu URL)
                $disk->putFileAs("{$basePath}/cover_images", $file, $filename);
                $coverPath = "{$basePath}/cover_images/{$filename}";
            }

            // 2) Creăm vehicle
            $vehicle = Vehicle::create([
                'id' => $vehicleId,
                'slug' => Str::slug($data['brand'] . '-' . $data['model'] . '-' . Str::random(6)),
                'owner_id' => $owner->id,
                'vehicle_type_id' => $data['vehicle_type_id'],
                'brand' => $data['brand'],
                'model' => $data['model'],
                'year' => $data['year'],
                'description' => $data['description'] ?? null,
                'fuel_type_id' => $data['fuel_type_id'],
                'transmission_id' => $data['transmission_id'],
                'autonomy_km' => $data['autonomy_km'] ?? null,
                'battery_capacity_kwh' => $data['battery_capacity_kwh'] ?? null,
                'max_speed_kph' => $data['max_speed_kph'] ?? null,
                'seats' => $data['seats'],
                'doors' => $data['doors'],
                'cargo_volume_liters' => $data['cargo_volume_liters'] ?? null,
                'license_plate' => $data['license_plate'] ?? null,
                'price_per_day' => $data['price_per_day'],
                'car_type' => $data['car_type'],
                'cover_image' => $coverPath,
                'gallery_images' => [],
                'is_verified' => false,
                'status' => 'pending',
            ]);

            // 3) Upload GALLERY (dacă e)
            $galleryRelPaths = [];
            if (!empty($data['gallery_images']) && is_array($data['gallery_images'])) {
                foreach ($data['gallery_images'] as $img) {
                    $ext = $img->getClientOriginalExtension() ?: 'jpg';
                    $filename = 'gallery_' . Str::uuid() . '.' . $ext;
                    $disk->putFileAs("{$basePath}/gallery", $img, $filename);
                    $galleryRelPaths[] = "{$basePath}/gallery/{$filename}";
                }
            }
            if ($galleryRelPaths) {
                $vehicle->update(['gallery_images' => $galleryRelPaths]);
            }

            // 4) Attach rental types
            if (!empty($data['rental_type_ids'])) {
                $vehicle->rentalTypes()->sync($data['rental_type_ids']);
            }

            // 5) Locații
            $this->attachLocationsByFields($vehicle, $data['locations'] ?? []);

            return redirect()
                ->route('user.cars.index')
                ->with('message', 'Mașina a fost adăugată cu succes.');
        });
    }

    public function attachLocationsByFields(Vehicle $vehicle, array $payload): void
    {
        foreach ($payload as $raw) {
            $attrs = $this->normalizeLoc($raw);

            // dacă toate câmpurile sunt null => ignoră
            if ($this->isEmptyLocation($attrs)) {
                continue;
            }

            // caută rând IDENTIC (inclusiv NULL); dacă nu există, îl creează
            $loc = Location::firstOrCreate($attrs);

            // atașează în pivot fără dubluri
            $vehicle->locations()->syncWithoutDetaching([$loc->id]);
        }
    }

    /**
     * Ținem doar câmpurile relevante; trim la stringuri;
     * '' => null; lat/long în float.
     */
    private function normalizeLoc(array $loc): array
    {
        $keys = ['name', 'address', 'city', 'postal_code', 'country', 'latitude', 'longitude'];

        $attrs = [];
        foreach ($keys as $k) {
            $v = $loc[$k] ?? null;

            if (is_string($v)) {
                $v = trim($v);
                if ($v === '') {
                    $v = null;
                }
            }

            if (in_array($k, ['latitude', 'longitude'], true) && $v !== null) {
                $v = (float) $v;
            }

            $attrs[$k] = $v;
        }

        return $attrs;
    }

    /** True dacă toate câmpurile sunt NULL. */
    private function isEmptyLocation(array $attrs): bool
    {
        foreach ($attrs as $v) {
            if ($v !== null) {
                return false;
            }
        }
        return true;
    }

    public function edit($carSlug)
    {
        $owner = Auth::user();
        $vehicle = $owner->vehicles()->where('slug', $carSlug)->firstOrFail();

        $vehicle->load(['locations', 'rentalTypes:id']); // ca să ai arrays
        return Inertia::render('Owner/Cars/Edit', [
            'vehicle' => $vehicle,
            'fuelTypes' => FuelType::select('id', 'name')->orderBy('name')->get(),
            'transmissions' => Transmission::select('id', 'name')->orderBy('name')->get(),
            'vehicleTypes' => VehicleType::select('id', 'name')->orderBy('name')->get(),
            'rentalTypes' => RentalType::select('id', 'label')->orderBy('label')->get(),
            'carTypes' => collect(CarType::cases())->map(fn($c) => ['value' => $c->value, 'label' => ucfirst($c->value)]),
        ]);
    }

    public function update(OwnerCarUpdateRequest $request, $carSlug)
    {
        $owner = Auth::user();
        $vehicle = $owner->vehicles()->where('slug', $carSlug)->firstOrFail();
        $data = $request->validated();

        $basePath = "vehicles/owner_{$owner->id}/vehicle_{$vehicle->id}";
        $disk = Storage::disk('aws-public');

        return DB::transaction(function () use ($data, $vehicle, $disk, $basePath) {

            $coverPath = $this->carService->handleCoverImageUpload($vehicle, $disk, $data, $basePath);

            $finalGallery = $this->carService->handleGalleryImagesUpload($data, $disk, $basePath, $vehicle);

            $vehicle->update([
                'vehicle_type_id' => $data['vehicle_type_id'],
                'brand' => $data['brand'],
                'model' => $data['model'],
                'year' => $data['year'],
                'description' => $data['description'] ?? null,
                'fuel_type_id' => $data['fuel_type_id'],
                'transmission_id' => $data['transmission_id'],
                'autonomy_km' => $data['autonomy_km'] ?? null,
                'battery_capacity_kwh' => $data['battery_capacity_kwh'] ?? null,
                'max_speed_kph' => $data['max_speed_kph'] ?? null,
                'seats' => (int) ($data['seats'] ?? 1),
                'doors' => (int) ($data['doors'] ?? 2),
                'cargo_volume_liters' => $data['cargo_volume_liters'] ?? null,
                'license_plate' => $data['license_plate'] ?? null,
                'price_per_day' => $data['price_per_day'],
                'car_type' => $data['car_type'],
                'cover_image' => $coverPath,
                'gallery_images' => $finalGallery,
            ]);

            /**
             * 4) Rental types
             */
            if (!empty($data['rental_type_ids'])) {
                $vehicle->rentalTypes()->sync($data['rental_type_ids']);
            } else {
                $vehicle->rentalTypes()->detach();
            }

            $this->attachLocationsByFields($vehicle, $data['locations'] ?? []);

            $this->carService->handleCarLocations($vehicle, $data);

            return redirect()
                ->route('user.cars.index')
                ->with('message', 'Mașina a fost actualizată cu succes.');
        });
    }

    public function documents(string $carSlug)
    {
        $owner = Auth::user();
        $vehicle = $owner->vehicles()->where('slug', $carSlug)->firstOrFail();

        $allowedTypes = DocumentType::carDocumentTypes();

        $existing = $vehicle->documents()->get();
        if ($existing->isEmpty()) {
            $documents = [];
        } else {
            $documents = $existing->map(fn($doc) => [
                'type' => $doc->type,
                'url' => route('vehicle-documents.view', ['path' => $doc->path]),
            ]);
        }

        $existingTypes = $existing->pluck('type')->unique()->toArray();
        $missingTypes = array_values(
            array_diff($allowedTypes, $existingTypes)
        );


        return Inertia::render('Owner/Cars/Documents', [
            'vehicle' => $vehicle,
            'documents' => $documents,
            'allowedTypes' => DocumentType::carDocumentTypes(),
            'missingTypes' => $missingTypes,
        ]);
    }

    public function uploadDocuments(Request $request)
    {
        $owner = auth()->user();
        $allowedTypes = $request->input('allowedTypes', []);
        $vehicleId = $request->input('vehicle_id');
        $vehicle = Vehicle::findOrFail($vehicleId);

        foreach ($allowedTypes as $type) {
            if ($request->hasFile($type)) {
                $file = $request->file($type);
                $extension = $file->getClientOriginalExtension();
                $filename = "document_vehicleId_{$vehicleId}_{$type}." . $extension;
                $path = "documents_vehicles/owner_{$owner->id}/{$filename}";

                // Șterge vechiul document dacă există
                $existing = $vehicle->documents()->where('type', $type)->first();
                if ($existing && Storage::disk('aws-private')->exists($existing->path)) {
                    Storage::disk('aws-private')->delete($existing->path);
                }

                // Salvează noul fișier
                Storage::disk('aws-private')->put($path, file_get_contents($file));

                if ($existing) {
                    $existing->update(['path' => $path]);
                } else {
                    $vehicle->documents()->create([
                        'id' => Uuid::uuid(),
                        'user_id' => $owner->id,
                        'type' => $type,
                        'path' => $path,
                        'vehicle_id' => $vehicleId,
                    ]);
                }
            }
        }
        return redirect()->back()->with('message', 'Documents uploaded.');
    }

    public function destroy($carId)
    {
        $owner = Auth::user();
        $car = $owner->vehicles()->findOrFail($carId);

        $car->delete();

        return redirect()->route('user.cars.index')
            ->with('message', 'Mașina a fost ștearsă cu succes.');
    }
}
