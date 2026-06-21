<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Event;
use App\Models\TicketCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users
        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@tiketacara.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Customer
        User::create([
            'name' => 'Yusuf Customer',
            'email' => 'customer@tiketacara.com',
            'password' => Hash::make('customer123'),
            'role' => 'customer',
        ]);

        // 2. Seed Events and their Ticket Categories
        $eventsData = [
            [
                'title' => 'Jakarta Neon Symphony 2026',
                'slug' => 'jakarta-neon-symphony-2026',
                'description' => '<p>Nikmati malam penuh keajaiban audio-visual dengan penampilan dari artis-artis papan atas lokal dan internasional. Panggung megah 360 derajat, sistem pencahayaan laser berteknologi tinggi, dan suara surround kualitas premium menanti Anda.</p><p>Konser ini dirancang untuk menyatukan pencinta musik dalam harmoni visual neon yang menyala dalam gelap.</p>',
                'date' => '2026-08-15 19:30:00',
                'location' => 'Stadion Utama Gelora Bung Karno, Jakarta',
                'image_path' => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'is_featured' => true,
                'categories' => [
                    ['name' => 'VIP Section', 'price' => 1500000, 'quota' => 50],
                    ['name' => 'Festival (Standing)', 'price' => 750000, 'quota' => 200],
                    ['name' => 'Tribune (Seated)', 'price' => 450000, 'quota' => 300],
                ]
            ],
            [
                'title' => 'Futurise Tech Summit 2026',
                'slug' => 'futurise-tech-summit-2026',
                'description' => '<p>Konferensi teknologi terbesar tahun ini. Membahas masa depan Artificial Intelligence, Web3, Quantum Computing, dan revolusi energi hijau. Dihadiri oleh pembicara kelas dunia dari Silicon Valley.</p><p>Dapatkan wawasan berharga, peluang networking dengan startup founder, dan pameran teknologi terbaru.</p>',
                'date' => '2026-09-05 09:00:00',
                'location' => 'Jakarta Convention Center (JCC), Hall A & B',
                'image_path' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'is_featured' => false,
                'categories' => [
                    ['name' => 'Platinum Pass (All-Access)', 'price' => 2500000, 'quota' => 30],
                    ['name' => 'Gold Pass (Seminar & Expo)', 'price' => 1250000, 'quota' => 100],
                    ['name' => 'Student Pass (Seminar Only)', 'price' => 350000, 'quota' => 50],
                ]
            ],
            [
                'title' => 'Art & Beyond: VR Exhibition',
                'slug' => 'art-and-beyond-vr-exhibition',
                'description' => '<p>Pameran seni interaktif yang menggabungkan lukisan klasik fisik dengan seni Virtual Reality. Rasakan sensasi berjalan masuk ke dalam lukisan Van Gogh dan berinteraksi langsung dengan karya seni surealisme modern.</p><p>Setiap tiket berlaku untuk sesi berdurasi 2 jam guna memastikan kenyamanan eksplorasi VR Anda.</p>',
                'date' => '2026-07-20 10:00:00',
                'location' => 'Galeri Nasional Indonesia, Gedung B, Jakarta',
                'image_path' => 'https://images.unsplash.com/photo-1460661419201-fd4cecdf8a8b?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'is_featured' => true,
                'categories' => [
                    ['name' => 'VIP Session + VR Catalog', 'price' => 250000, 'quota' => 25],
                    ['name' => 'Regular Session', 'price' => 120000, 'quota' => 120],
                ]
            ],
            [
                'title' => 'Nusa Arena: Esports Championship 2026',
                'slug' => 'nusa-arena-esports-championship-2026',
                'description' => '<p>Turnamen e-sports terbesar di Asia Tenggara mempertemukan tim-tim profesional Mobile Legends dan Valorant terbaik. Saksikan perebutan piala juara dunia dan total hadiah Rp 1 Miliar secara langsung!</p><p>Nikmati juga festival cosplay, pameran hardware gaming, dan meet & greet dengan pro player favorit Anda.</p>',
                'date' => '2026-10-12 13:00:00',
                'location' => 'ICE BSD Hall 5, Tangerang',
                'image_path' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'is_featured' => false,
                'categories' => [
                    ['name' => 'VIP Arena Side (With Goodie Bag)', 'price' => 600000, 'quota' => 40],
                    ['name' => 'General Admission (Standard)', 'price' => 200000, 'quota' => 150],
                ]
            ]
        ];

        foreach ($eventsData as $eventInfo) {
            $categories = $eventInfo['categories'];
            unset($eventInfo['categories']);

            $event = Event::create($eventInfo);

            foreach ($categories as $cat) {
                TicketCategory::create([
                    'event_id' => $event->id,
                    'name' => $cat['name'],
                    'price' => $cat['price'],
                    'total_quota' => $cat['quota'],
                    'available_quota' => $cat['quota'],
                ]);
            }
        }
    }
}
