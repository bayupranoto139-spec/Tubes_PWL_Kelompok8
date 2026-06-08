<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Queue;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class QueueService
{
    /**
     * Generate next queue_number for a given doctor on a given date.
     * Scoped per doctor per day.
     */
    public static function nextNumber(int $doctorId, string $date): int
    {
        $max = Queue::whereDate('queue_date', $date)
            ->whereHas('appointment', fn ($q) => $q->where('doctor_id', $doctorId))
            ->max('queue_number');

        return ($max ?? 0) + 1;
    }

    /**
     * Create a queue entry for an appointment.
     * Idempotent — returns existing queue if already created.
     *
     * Priority:
     *  1 = appointment (pasien dengan booking sebelumnya, on-time)
     *  2 = walk-in / terlambat (tanpa booking atau datang terlambat)
     */
    public static function createForAppointment(Appointment $appointment, bool $isWalkIn = false): Queue
    {
        if ($existing = $appointment->queue) {
            return $existing;
        }

        // Jika tidak di-override, fallback ke cek schedule_id
        if (!$isWalkIn) {
            $isWalkIn = is_null($appointment->schedule_id);
        }

        $queueDate = Carbon::parse($appointment->scheduled_at)->toDateString();
        $doctorId  = $appointment->doctor_id;

        return DB::transaction(function () use ($appointment, $isWalkIn, $queueDate, $doctorId) {
            $number = self::nextNumber($doctorId, $queueDate);

            return Queue::create([
                'appointment_id' => $appointment->id,
                'queue_date'     => $queueDate,
                'queue_number'   => $number,
                'type'           => $isWalkIn ? 'walk_in' : 'appointment',
                'priority'       => $isWalkIn ? 2 : 1,
                'status'         => 'waiting',
            ]);
        });
    }
}