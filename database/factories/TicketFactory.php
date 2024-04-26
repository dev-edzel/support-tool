<?php

namespace Database\Factories;

use App\Models\TicketInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ticket_info_id' => TicketInfo::factory()->create()->id,
            'ticket_number' => $this->faker->randomElement([
                'TEST' . rand(1000000, 999999999)
            ]),
            'status' => $this->faker->randomElement(
                [0, 1, 2, 3, 4]
            )
        ];
    }
}
