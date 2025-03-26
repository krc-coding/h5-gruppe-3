<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Devices;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Data>
 */
class DataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $device = Devices::all()->random();

        $people = $this->faker->numberBetween(1, 100);
        $products_pr_person = $this->faker->numberBetween(1, 20);
        $total_value = $people * $products_pr_person * $this->faker->randomFloat(2, 1, 1000);
        $packages_received = $this->faker->numberBetween(0, 10);
        $packages_delivered = $this->faker->numberBetween(0, 10);
        $data_recorded_at = $this->faker->dateTimeBetween('-1 year', 'now', 'UTC');

        // Randomly distribute the total value of the products among the categories
        $category_total = 1;
        $category_array = [];
        $category_array['meat'] = $this->faker->randomFloat(2, 0, $category_total);
        $category_total -= $category_array['meat'];
        $category_array['milk products'] = $this->faker->randomFloat(2, 0, $category_total);
        $category_total -= $category_array['milk products'];
        $category_array['vegetables'] = $this->faker->randomFloat(2, 0, $category_total);
        $category_total -= $category_array['vegetables'];
        $category_array['candy'] = $this->faker->randomFloat(2, 0, $category_total);

        $product_categories = json_encode($category_array);

        return [
            'people' => $people,
            'products_pr_person' => $products_pr_person,
            'total_value' => $total_value,
            'product_categories' => $product_categories,
            'packages_received' => $packages_received,
            'packages_delivered' => $packages_delivered,
            'device_id' => $device->id,
            'data_recorded_at' => date_format($data_recorded_at, "Y-m-d H:i:s.u"),
        ];
    }
}
