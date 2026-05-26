<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Citas>
 */
class AppointmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'document' => fake()->numerify('##########'),
            'appoiment_date' => fake()->dateTimeBetween('now', '+1 month'),
            'call_disposition' =>  fake()->randomElement([
                'resolved',
                'resolved',
                'resolved',
                'no_answer',
                'callback_required',
                'escalated',
            ]),
        ];
    }
}
