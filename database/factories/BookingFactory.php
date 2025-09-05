<?php

namespace Database\Factories;

use App\Models\{User,Service,Booking};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $clientIds = User::role('USER')->pluck('id')->all();
        $providerIds = User::role('SERVICE-PROVIDER')->pluck('id')->all();
        $serviceIds = Service::where('active', true)->pluck('id')->all();
        
        return [
            'client_id' => $clientIds[rand(0, count($clientIds) - 1)],
            'provider_id' => $providerIds[rand(0, count($providerIds) - 1)],
            'service_id' => $serviceIds[rand(0, count($serviceIds) - 1)],
            'booking_number' => $this->generateUniqueBookingNumber(),
            'price' => [1000, 10000][rand(0, 1)],
            'meeting_url' => uniqid(true),
            'start_date' => now(),
            'end_date' => now()->addHour(),
            'status' => 'Pending',
        ];
    }

    private function generateUniqueBookingNumber()
    {
        do {
            $number = 'BOOK-' . strtoupper(uniqid());
        } while (Booking::where('booking_number', $number)->exists());

        return $number;
    }
}
