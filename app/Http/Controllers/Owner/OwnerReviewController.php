<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OwnerReviewController extends Controller
{
    public function index(Request $request)
    {
        $key = $request->input('key', 'reviewed_at');
        $order = $request->input('order', 'desc');
        $search = $request->input('search');

        $owner = auth()->user();
        $rawReviews = Review::with(['reviewer', 'vehicle'])
            ->where('owner_id', $owner->id)
            ->latest('reviewed_at')
            ->get();

        // pentru calcule
        $totalReviews = $rawReviews->count();
        $averageRating = round($rawReviews->avg('rating'), 1);
        $ratingBreakdownRaw = $rawReviews->groupBy(function ($review) {
            return floor($review->rating);
        });

        // pentru frontend
        $allowedKeys = ['rating', 'reviewed_at'];
        $key = in_array($key, $allowedKeys) ? $key : 'reviewed_at';

        $reviews = Review::with(['reviewer', 'vehicle'])
            ->where('owner_id', $owner->id)
            ->when($search, function ($query) use ($search) {
                $query->whereHas('vehicle', function ($q) use ($search) {
                    $q->where(function ($subQ) use ($search) {
                        $subQ->where('brand', 'like', "%{$search}%")
                            ->orWhere('model', 'like', "%{$search}%");
                    });
                });
            })
            ->orderBy($key, $order)
            ->paginate(5)
            ->through(function ($review) {
                return [
                    'id' => $review->id,
                    'vehicle_name' => $review->vehicle ? $review->vehicle->brand . ' ' . $review->vehicle->model : '—',
                    'client_name' => $review->reviewer->name ?? '—',
                    'rating' => $review->rating,
                    'reviewed_at' => $review->reviewed_at->format('Y-m-d'),
                    'details' => [
                        ['label' => "Titlu", 'value' => $review->title],
                        ['label' => "Descriere", 'value' => $review->description]
                    ]
                ];
            });

        $ratingBreakdown = collect([5, 4, 3, 2, 1])->mapWithKeys(function ($star) use ($ratingBreakdownRaw, $totalReviews) {
            $count = isset($ratingBreakdownRaw[$star]) ? $ratingBreakdownRaw[$star]->count() : 0;
            $percent = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;
            return [$star => $percent];
        });

        // Rating mediu per mașină 
        $carRatings = $rawReviews->groupBy('car_id')->map(function ($reviewsForCar) {
            return [
                'car_id' => $reviewsForCar->first()->vehicle->id,
                'car_model' => $reviewsForCar->first()->vehicle->brand . ' ' . $reviewsForCar->first()->vehicle->model,
                'average_rating' => round($reviewsForCar->avg('rating'), 1),
                'reviews_count' => $reviewsForCar->count(),
            ];
        })->values();

        return Inertia::render('Owner/Reviews/Index', [
            'averageRating' => $averageRating,
            'carRatings' => $carRatings,
            'nr_reviews' => $totalReviews,
            'reviews' => $reviews,
            'ratingBreakdown' => $ratingBreakdown,
            'prevSearch' => $search
        ]);
    }

}
