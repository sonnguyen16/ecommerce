<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Product;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        // Data arrays
        $products = [
            [
                'img' => "product1.png",
                'name' => "Một thoáng ta rực rỡ ở nhân gian",
            ],
            [
                'img' => "product2.png",
                'name' => "Máy đọc sách New Kindle chính hãng",
            ],
            [
                'img' => "product3.png",
                'name' => "Túi nước giặt Omo 6kg hương mẫu ngẫu nhiên",
            ],
            [
                'img' => "product4.png",
                'name' => "[Mua 2 giảm 50K] Bộ 3 hộp sữa bột Ensure Gold 850g",
            ],
            [
                'img' => "product5.png",
                'name' => "Chảo inox nguyên khối Sunhouse 24cm",
            ],
            [
                'img' => "product6.png",
                'name' => "Máy ép chậm Kuvings B6000 chính hãng",
            ],
            [
                'img' => "product7.png",
                'name' => "Mặt nạ hỗ trợ trị mụn và dưỡng ẩm Some By Mi Yuja Niacin 30 Days Blemish Care Serum Mask",
            ],
            [
                'img' => "product8.png",
                'name' => "Loa Harman Kardon Onyx Studio 6 chính hãng",
            ],
            [
                'img' => "product9.png",
                'name' => "Nước cân bằng da Some By Mi Yuja Niacin 30 Days Brightening Toner",
            ],
            [
                'img' => "product10.png",
                'name' => "Sữa rửa mặt Some By Mi Yuja Niacin 30 Days Brightening Face Wash",
            ],
            [
                'img' => "product11.png",
                'name' => "Siêu mặt nạ Some By Mi Yuja Niacin 30 Days Brightening Sleeping Mask",
            ],
            [
                'img' => "product12.png",
                'name' => "Sữa Meji 0% đường 200ml hương vani hộp 6 lon",
            ]
        ];


        // Loop to insert products
        foreach ($products as $index => $product) {
            $price = rand(100000, 1000000); // Random price between 100,000 and 1,000,000
            do {
                $salePrice = rand(100000, 1000000); // Random sale price between 100,000 and 1,000,000
            } while ($salePrice >= $price); // Sale price must be less than price
            Product::create([
                'name' => $product['name'],
                'slug' => Str::slug($product['name']),
                'description' => $faker->text(2000),
                'unit' => 'pcs',
                'price' => $price, // Random price between 100,000 and 1,000,000
                'sale_price' => $salePrice, // Random sale price between 100,000 and 1,000,000
                'quantity' => rand(10, 100), // Random quantity between 10 and 100
                'sold' => rand(0, 50), // Random sold count between 0 and 50
                'thumbnail' => $product['img'],
                'attributes' => json_encode($this->generateRandomAttributes()), // Random attributes
                'category_id' => rand(1, 10), // Random category_id from 1 to 10
                'shop_id' => 1, // Shop ID is 1
                'seo_title' => $product['name'],
                'seo_description' => $faker->text(400),
                'seo_url' => Str::slug($product['name']),
            ]);
        }
    }

    /**
     * Generate random attributes for a product.
     */
    private function generateRandomAttributes()
    {
        // Define possible attributes
        $colors = ['Red', 'Blue', 'Green', 'Black', 'White'];
        $sizes = ['S', 'M', 'L', 'XL'];
        $materials = ['Cotton', 'Polyester', 'Leather', 'Plastic'];
        $origins = ['Vietnam', 'USA', 'China', 'Germany', 'Japan'];
        $brands = ['Nike', 'Adidas', 'Samsung', 'Apple', 'Sony'];

        // Generate random attributes
        return [
            'Màu sắc' => $colors[array_rand($colors)], // Random color
            'Kích cỡ' => $sizes[array_rand($sizes)], // Random size
            'Chất liệu' => $materials[array_rand($materials)], // Random material
            'Nguồn gốc' => $origins[array_rand($origins)], // Random origin
            'Thương hiệu' => $brands[array_rand($brands)], // Random brand
            'Bảo hành' => rand(6, 24) . ' months', // Random warranty between 6 and 24 months
            'Cân nặng' => rand(1, 5) . ' kg', // Random weight between 1 and 5 kg
        ];
    }
}
