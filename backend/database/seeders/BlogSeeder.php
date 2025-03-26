<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $blogs = [
            [
                'title' => 'Hướng dẫn đặt hàng',
                'content' => '<h2>Hướng dẫn đặt hàng trên BRTGo</h2>
                <p>1. Đăng nhập tài khoản</p>
                <p>2. Chọn sản phẩm muốn mua</p>
                <p>3. Thêm vào giỏ hàng</p>
                <p>4. Kiểm tra giỏ hàng và tiến hành thanh toán</p>
                <p>5. Chọn phương thức thanh toán và vận chuyển</p>
                <p>6. Xác nhận đơn hàng</p>',
                'is_public' => true,
                'thumbnail' => 'product1.png'
            ],
            [
                'title' => 'Chính sách đổi trả',
                'content' => '<h2>Chính sách đổi trả hàng</h2>
                <p>1. Thời gian đổi trả: 30 ngày kể từ ngày nhận hàng</p>
                <p>2. Điều kiện đổi trả:</p>
                <ul>
                    <li>Sản phẩm còn nguyên vẹn, chưa qua sử dụng</li>
                    <li>Còn đầy đủ hộp, phụ kiện, tem mác</li>
                    <li>Không bị lỗi do người dùng</li>
                </ul>
                <p>3. Quy trình đổi trả:</p>
                <p>4. Chi phí đổi trả:</p>',
                'is_public' => true,
                'thumbnail' => 'product2.png'
            ],
            [
                'title' => 'Câu hỏi thường gặp',
                'content' => '<h2>Những câu hỏi thường gặp</h2>
                <h3>1. Làm sao để theo dõi đơn hàng?</h3>
                <p>Bạn có thể theo dõi đơn hàng bằng cách:</p>
                <ul>
                    <li>Đăng nhập vào tài khoản</li>
                    <li>Vào mục "Đơn hàng của tôi"</li>
                    <li>Chọn đơn hàng cần xem</li>
                </ul>
                <h3>2. Thời gian giao hàng bao lâu?</h3>
                <p>Thời gian giao hàng tùy thuộc vào địa chỉ và phương thức vận chuyển:</p>
                <ul>
                    <li>Nội thành: 1-2 ngày</li>
                    <li>Ngoại tỉnh: 3-5 ngày</li>
                </ul>',
                'is_public' => true,
                'thumbnail' => 'product3.png'
            ]
        ];

        foreach ($blogs as $blog) {
            Blog::create($blog);
        }
    }
}
