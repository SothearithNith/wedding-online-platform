<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Rsvp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RsvpController extends Controller
{
    public function store(Request $request, Invitation $invitation): JsonResponse
    {
        $data = $request->validate([
            'guest_name'   => ['required', 'string', 'max:60'],
            'attending'    => ['required', 'boolean'],
            'guests_count' => ['nullable', 'integer', 'min:0', 'max:10'],
            'message'      => ['nullable', 'string', 'max:200'],
        ]);

        $attending = (bool) $data['attending'];

        try {
            Rsvp::create([
                'invitation_id' => $invitation->id,
                'guest_name'    => trim($data['guest_name']),
                'attending'     => $attending,
                'guests_count'  => $attending ? max(1, (int) ($data['guests_count'] ?? 1)) : 0,
                'message'       => isset($data['message']) ? trim($data['message']) : null,
                'ip'            => $request->ip(),
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['ok' => true], 201);
    }
}
