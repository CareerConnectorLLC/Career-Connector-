<?php

namespace App\Http\Controllers\Frontend\Provider;

use DateTime;
use DateInterval;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AvailabilityController extends Controller
{
    public function index(Request $request)
    {
        $availabilityData = json_decode($request->user()->availability->timings, true) ?? [];

        $availability = [];
        
        foreach ($availabilityData as $day => $times) {
            if ($times) {
                $availability[] = [
                    'day' => $day,
                    'from_time' => date('H:i', strtotime(reset($times))),
                    'to_time' => date('H:i', strtotime(end($times))),
                ];
            }
        }

        return Inertia::render('Frontend/provider/MyAvailability', [
            'availability' => $availability,
            'pageTitle' => env('APP_NAME') . ' | Provider Availability'
        ]);
    }

    public function store(Request $request)
    {
        $customErrors = [];
        $hasAtLeastOneCompleteEntry = false;

        $submittedAvailability = $request->input('availability', []);

        if (!is_array($submittedAvailability) || empty($submittedAvailability)) {
            $customErrors['availability'] = ['Please provide availability data.'];
        } else {
            foreach ($submittedAvailability as $index => $item) {
                $day = $item['day'] ?? null;
                $fromTime = $item['from_time'] ?? null;
                $toTime = $item['to_time'] ?? null;

                // Rule 1: If one is present, the other must be too.
                if (!empty($fromTime) && empty($toTime)) {
                    $customErrors["availability.{$day}.to_time"] = ["End time for {$day} is required when start time is present."];
                } elseif (empty($fromTime) && !empty($toTime)) {
                    $customErrors["availability.{$day}.from_time"] = ["Start time for {$day} is required when end time is present."];
                }

                // Rule 2: If both are present, validate format and 'after'
                if (!empty($fromTime) && !empty($toTime)) {
                    $dayRules = [
                        'from_time' => 'date_format:H:i',
                        'to_time' => 'date_format:H:i|after:from_time',
                    ];

                    $dayMessages = [
                        'from_time.date_format' => "Invalid start time format for {$day}.",
                        'to_time.date_format' => "Invalid end time format for {$day}.",
                        'to_time.after' => "End time for {$day} must be after start time.",
                    ];

                    $dayValidator = Validator::make($item, $dayRules, $dayMessages);

                    if ($dayValidator->fails()) {
                        foreach ($dayValidator->errors()->messages() as $key => $messages) {
                            $customErrors["availability.{$day}.{$key}"] = $messages;
                        }
                    } else {
                        // If both are present and pass format/after validation, it's a complete entry
                        $hasAtLeastOneCompleteEntry = true;
                    }
                }
            }
        }

        // Rule 3: At least one complete pair must exist
        if (!$hasAtLeastOneCompleteEntry && empty($customErrors)) {
            $customErrors['availability'] = ['Please enter at least one complete start and end time pair.'];
        }

        if (!empty($customErrors)) {
            return back()->withErrors($customErrors);
        }

        // Filter out entries where either from_time or to_time is missing or had validation errors
        $filteredAvailability = array_filter($submittedAvailability, function ($item) use ($customErrors) {
            $day = $item['day'] ?? null;
            $fromTime = $item['from_time'] ?? null;
            $toTime = $item['to_time'] ?? null;

            // Only include if both are present AND there were no specific errors for this day's times
            $hasNoErrors = !isset($customErrors["availability.{$day}.from_time"]) && !isset($customErrors["availability.{$day}.to_time"]);

            return !empty($fromTime) && !empty($toTime) && $hasNoErrors;
        });

        $allDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $finalAvailability = array_fill_keys($allDays, null); // Initialize all days to null

        foreach ($filteredAvailability as $item) {
            $day = $item['day'];
            $fromTimeStr = $item['from_time'];
            $toTimeStr = $item['to_time'];

            $from = DateTime::createFromFormat('H:i', $fromTimeStr);
            $to = DateTime::createFromFormat('H:i', $toTimeStr);

            $timeSlots = [];
            $current = clone $from;

            while ($current <= $to) {
                $timeSlots[] = $current->format('h:i a'); // Format to 12-hour with am/pm
                $current->add(new DateInterval('PT1H')); // Add 1 hour
            }
            $finalAvailability[$day] = $timeSlots;
        }

        // Save the record
        $user = $request->user();
        $user->availability->timings = json_encode($finalAvailability);
        $user->availability->save();

        return back()->with('success', 'Availability updated successfully.');
    }
}
