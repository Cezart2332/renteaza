<?php

namespace App\Actions\Bookings;

use App\Enums\ReservationStatus;
use App\Models\Booking;
use App\Models\CheckinPhoto;
use App\Models\CheckinSubmission;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SubmitCheckInPhotosAction
{
    /**
     * @param  Booking $booking
     * @param  UploadedFile[] $photos exactly 4 images
     * @param  int $userId
     * @return CheckinSubmission
     */
    public function execute(Booking $booking, array $photos, int $userId, string $type): CheckinSubmission
    {
        return DB::transaction(function () use ($booking, $photos, $userId, $type) {
            // 1) creează submission (pending)
            $submission = CheckinSubmission::create([
                'booking_id'   => $booking->id,
                'submitted_by' => $userId,
                'status'       => 'pending',
                'notes'        => null,
                'type'         => $type,
            ]);

            // 2) salvează pozele (pe disk-ul "public" pentru început)
            //    ex: bookings/{booking_id}/checkin/{submission_id}/slot_1.jpg
            $baseDir = "bookings/{$booking->id}/checkin/{$submission->id}";

            foreach ($photos as $idx => $file) {
                $position = $idx + 1;

                $ext = $file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'jpg';
                $filename = 'slot_' . $position . '.' . Str::lower($ext);

                $path = Storage::disk('public')->putFileAs($baseDir, $file, $filename);

                CheckinPhoto::create([
                    'submission_id' => $submission->id,
                    'position'      => $position,
                    'path'          => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime'          => $file->getMimeType(),
                    'size'          => $file->getSize(),
                    // width/height le putem popula ulterior după un job de procesare
                ]);
            }

            // 3) actualizează booking status
            $booking->update([
                'status' => ReservationStatus::CheckInSubmitted->value,
            ]);

            return $submission;
        });
    }
}
