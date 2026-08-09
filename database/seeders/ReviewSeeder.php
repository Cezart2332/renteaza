<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reviewers = User::whereHas('roles', fn ($q) => $q->where('name', 'client'))->get();

    $cars = Car::with('owner')->get();

    foreach ($cars as $car) {
        // Ia un client random (diferit de owner)
        $reviewer = $reviewers->where('id', '!=', $car->owner_id)->random();

        Review::create([
            'id' => \Str::uuid(),
            'reviewer_id' => $reviewer->id,
            'owner_id' => $car->owner_id,
            'car_id' => $car->id,
            'rating' => fake()->randomFloat(1, 1, 5),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'reviewed_at' => now()->subDays(rand(1, 30)),
        ]);
    }
}
}
