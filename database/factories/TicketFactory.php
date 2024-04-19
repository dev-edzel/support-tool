<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\TicketType;
use Illuminate\Support\Str;
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
            'first_name' => $this->faker->firstName(),
            'middle_name' => $this->faker->lastName(),
            'last_name' => $this->faker->lastName(),
            'address' => $this->faker->streetAddress(),
            'number' => $this->faker->phoneNumber(),
            'email' => $this->faker->email(),
            'ticket_type_id' => TicketType::factory()->create()->id,
            'category_id' => Category::factory()->create()->id,
            'sub_category_id' => SubCategory::factory()->create()->id,
            'subject' => $this->faker->sentence(),
            'ref_no' => $this->faker->randomElement(
                ['TICKET' . strtoupper(Str::random(6))]
            ),
            'concern' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(
                ['OPEN', 'ASSIGNED', 'ON HOLD', 'CLOSED', 'CANCELLED']
            )
        ];
    }
}
