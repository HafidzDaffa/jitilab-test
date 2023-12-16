<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Blog;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Blog::create([
            'title' => 'Aplikasi Web Adalah',
            'description' => 'Aplikasi web adalah perangkat lunak yang berjalan di browser web Anda. Bisnis harus bertukar informasi dan memberikan layanan dari jarak jauh.',
            'image' => 'web.jpg'
        ]);

        Blog::create([
            'title' => 'Aplikasi Mobile Adalah',
            'description' => 'Mobile apps merupakan perangkat lunak berupa aplikasi yang dikembangkan menggunakan program komputerisasi untuk disematkan pada perangkat mobile seperti ponsel, tablet dan jam tangan digital.',
            'image' => 'mobile.jpg'
        ]);
    }
}
