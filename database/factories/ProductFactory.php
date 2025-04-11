<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

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
    
    protected $model = Product::class;
     
    public function definition(): array
    {
        return [
            'name' => $this->faker->text,
            'shop_id'=>6,
            'brand_id'=>4,
            'unit'=>1,
            'thumbnail_img'=>46,
            'product_status'=> 'ready',
            'description'=>$this->faker->text,
            'lowest_price'=>64900,
            'discount'=>0,
            'stock'=>1,
            'lowest_price'=>1000,
            'highest_price'=>2000,
            'discount'=>0,
            'stock'=>1,
            'min_qty'=>2,
            'max_qty'=>4,
            'weight'=>10,
            'height'=>10,
            'length'=>10,
            'width'=>20,
        ];
    }
}