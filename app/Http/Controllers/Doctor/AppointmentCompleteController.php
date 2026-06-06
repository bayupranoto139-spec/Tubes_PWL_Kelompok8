<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentCompleteController extends Controller
{
    public function complete(Request $request, $appointmentId)
    {
        $doctorId = auth()->user()?->doctor?->id;
        if (! $doctorId) {
            abort(403);
        }

        $appointmentId = (int) $appointmentId;

        DB::transaction(function () use ($appointmentId, $doctorId) {
            $appointment = DB::table('appointments')
                ->where('id', $appointmentId)
                ->where('doctor_id', $doctorId)
                ->lockForUpdate()
                ->first();

            if (! $appointment) {
                abort(403, 'Appointment tidak ditemukan untuk dokter ini.');
            }

            // hanya boleh mengubah appointment yang belum selesai
            if (in_array($appointment->status, ['completed'], true)) {
                return;
            }

            DB::table('appointments')
                ->where('id', $appointmentId)
                ->where('doctor_id', $doctorId)
                ->update([
                    'status' => 'completed',
                    'updated_at' => now(),
                ]);
        });

        return redirect()->route('doctor.today')->with('success', 'Appointment berhasil diselesaikan (complete).');
    }
}
