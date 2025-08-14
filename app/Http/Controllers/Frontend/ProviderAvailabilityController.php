<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ProviderAvailability;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProviderAvailabilityController extends Controller
{
    /**
     * Get the available time slots for a provider on a specific date,
     * considering their general availability and existing bookings.
     */
    public function getAvailability(Request $request, $providerId, $date)
    {
        // Determine the day of the week (e.g., 'Wed') from the given date.
        $dayOfWeek = Carbon::parse($date)->format('D');

        // Fetch the provider's general weekly availability.
        $providerAvailability = ProviderAvailability::where('provider_id', $providerId)->first();

        // If there's no availability record, return empty.
        if (!$providerAvailability) {
            return response()->json([]);
        }

        // Decode the JSON string into an associative array.
        $timings = json_decode($providerAvailability->timings, true);

        // If there are no slots for that specific day, return empty.
        if (!isset($timings[$dayOfWeek])) {
            return response()->json([]);
        }

        $allTimeSlots = $timings[$dayOfWeek];

        // Fetch all confirmed bookings for the provider on the given date.
        $bookings = Booking::where('provider_id', $providerId)
            ->whereDate('start_date', $date)
            ->where('status', 'Confirmed')
            ->get(['start_date', 'end_date']);

        // Create an array of CarbonPeriod objects for each booking to represent booked time ranges.
        $bookedPeriods = [];
        foreach ($bookings as $booking) {
            $bookedPeriods[] = CarbonPeriod::create(
                Carbon::parse($booking->start_date),
                '1 hour',
                Carbon::parse($booking->end_date)->addHour()
            );
        }

        // Flatten the booked periods into an array of individual 1-hour time slots.
        $bookedSlots = [];
        foreach ($bookedPeriods as $bookedPeriod) {
            foreach ($bookedPeriod as $period) {
                $bookedSlots[] = $period->format('h:i a');
            }
        }

        // Determine the available time slots by filtering out the booked slots.
        $availableSlots = [];
        foreach ($allTimeSlots as $timeSlot) {
            if (!in_array($timeSlot, $bookedSlots)) {
                $availableSlots[] = $timeSlot;
            }
        }

        // Return the final list of available slots as a JSON response.
        return response()->json([
            'available_slots' => $availableSlots,
            'booked_slots' => $bookedSlots,
        ]);
    }

    public function checkAvailability(Request $request)
    {
        // It's good practice to validate incoming API requests.
        $validated = $request->validate([
            'provider_id' => 'required|exists:users,id',
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:h:i a',
            'duration' => 'required|integer|min:1',
        ]);

        $startsAt = Carbon::parse($validated['date'] . ' ' . $validated['time']);
        $endsAt = $startsAt->clone()->addHours($validated['duration']);

        // This is a more robust way to check for any overlapping bookings.
        $isBooked = Booking::where('provider_id', $validated['provider_id'])
            ->where('status', 'Confirmed')
            ->where(function ($query) use ($startsAt, $endsAt) {
                $query->where('start_date', '<', $endsAt)
                      ->where('end_date', '>', $startsAt);
            })
            ->exists();

        if ($isBooked) {
            return response()->json(['available' => false, 'message' => 'This time slot is no longer available. Please select another time.']);
        }

        return response()->json(['available' => true]);
    }
}
