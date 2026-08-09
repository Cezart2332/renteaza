<?php

namespace App\Services;

use App\Models\Vehicle;
use Str;

class CarService
{
    public function handleCoverImageUpload(Vehicle $vehicle, $disk, $data, $basePath): string
    {
        $coverPath = $vehicle->cover_image;
        if (!empty($data['cover_image'])) {
            // înlocuim coverul
            if ($coverPath) {
                $disk->delete($coverPath);
            }
            $file = $data['cover_image'];
            $ext = $file->getClientOriginalExtension() ?: 'jpg';
            $filename = 'cover_' . Str::uuid() . '.' . $ext;
            $disk->putFileAs("{$basePath}/cover_images", $file, $filename);
            $coverPath = "{$basePath}/cover_images/{$filename}";
        }

        return $coverPath;
    }

    public function handleGalleryImagesUpload($data, $disk, $basePath, Vehicle $vehicle): array
    {
        $newImages = [];
        if (!empty($data['new_images_to_save']) && is_array($data['new_images_to_save'])) {
            foreach ($data['new_images_to_save'] as $img) {
                $ext = $img->getClientOriginalExtension() ?: 'jpg';
                $filename = 'gallery_' . Str::uuid() . '.' . $ext;
                $disk->putFileAs("{$basePath}/gallery", $img, $filename);
                $newImages[] = "{$basePath}/gallery/{$filename}";
            }
        }

        if (!empty($data['images_to_remove']) && is_array($data['images_to_remove'])) {
            foreach ($data['images_to_remove'] as $imgPath) {
                if ($disk->exists($imgPath)) {
                    $disk->delete($imgPath);
                }

            }
        }

        // merge with existing gallery images and remove the images that were removed
        $existingGallery = $vehicle->gallery_images ?? [];
        $finalGallery = array_merge($existingGallery, $newImages);
        $finalGallery = array_diff($finalGallery, $data['images_to_remove'] ?? []);

        // make it array not object
        $finalGallery = array_values($finalGallery);

        return $finalGallery;
    }

    public function handleCarLocations(Vehicle $vehicle, $data)
    {
        $vehicleLocations = $vehicle->locations->pluck('id')->toArray();
        $locationIdsRequest = $data['locations'] ? array_filter(array_column($data['locations'], 'id')) : [];
        $locationsToDetach = array_diff($vehicleLocations, $locationIdsRequest);
        if (!empty($locationsToDetach)) {
            $vehicle->locations()->detach($locationsToDetach);
        }
    }
}