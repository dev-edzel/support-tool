<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\TicketType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TicketInfo>
 */
class TicketInfoFactory extends Factory
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
                ['PPG' . strtoupper(Str::random(10))]
            ),
            'concern' => $this->faker->sentence(),
        ];
    }
}
