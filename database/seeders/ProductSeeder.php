<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

//Using product model
use App\Models\Product;
//Using category model
use App\Models\Category;
// Using validator

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        
         //assigning values to create a product with faker
         $faker = Faker::create();

         for($i=1; $i<=500; $i++)
         { //product object
            $product = new Product;
            // category object
            $category = new Category;
            $product->name = $faker->name;
            $product->price = $faker->randomDigit;
            //Using firstOrCreate to create new category if not existed and getting its id (existing/new)
             $product->category =$category->firstOrCreate(['name'=>$faker->name])->id;
            $product->description = $faker->text;
            $product->save();
        }
    }
        
}
