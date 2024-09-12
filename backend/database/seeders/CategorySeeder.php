<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'Đồ chơi - Mẹ & bé',
                'slug' => Str::slug('Đồ chơi - Mẹ & bé'),
                'image' => 'toy.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Điện thoại - Máy tính bảng',
                'slug' => Str::slug('Điện thoại - Máy tính bảng'),
                'image' => 'mobile.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nhà sách Gosell',
                'slug' => Str::slug('Nhà sách Gosell'),
                'image' => 'sach.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nhà cửa đời sống',
                'slug' => Str::slug('Nhà cửa đời sống'),
                'image' => 'cook.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Thiết bị - Phụ kiện',
                'slug' => Str::slug('Thiết bị - Phụ kiện'),
                'image' => 'ear.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Điện gia dụng',
                'slug' => Str::slug('Điện gia dụng'),
                'image' => 'electric.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Làm Đẹp - Sức khỏe',
                'slug' => Str::slug('Làm Đẹp - Sức khỏe'),
                'image' => 'beauty.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ô tô - Xe máy - Xe đạp',
                'slug' => Str::slug('Ô tô - Xe máy - Xe đạp'),
                'image' => 'vehicle.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Thời trang nữ',
                'slug' => Str::slug('Thời trang nữ'),
                'image' => 'fashion.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bách hóa online',
                'slug' => Str::slug('Bách hóa online'),
                'image' => 'gocery.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
