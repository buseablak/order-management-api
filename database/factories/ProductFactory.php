<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $products = ['Telefon', 'Kamera', 'Televizyon', 'Bilgisayar', 'Kulaklık','Gözlük','Klavye','Tablet','Ajanda','Kalem'];

        return [
            'product_name' => fake()->unique()->randomElement($products),
            'category_id' => fake()->numberBetween(1,3),
            'stock' => fake()->numberBetween(5,20),
            'product_price' => fake()->randomFloat(2,100,3000)

        ];
    }
}
