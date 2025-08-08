<?php

namespace Database\Factories;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    protected $model = Car::class;

    public function definition(): array
    {
        $models = [
            'Toyota Camry',
            'Honda Accord',
            'BMW X5',
            'Mercedes E-Class',
            'Audi A6',
            'Lexus RX',
            'Ford Focus',
            'Volkswagen Golf',
        ];

        return [
            'model' => $this->faker->randomElement($models),
            'category_id' => $this->faker->numberBetween(1, 3),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => now(),
        ];
    }
}
