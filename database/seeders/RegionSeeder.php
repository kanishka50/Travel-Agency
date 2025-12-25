<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regions = [
            // Western Province - Major tourist areas
            ['name' => 'Colombo', 'province' => 'Western Province', 'sort_order' => 1],
            ['name' => 'Negombo', 'province' => 'Western Province', 'sort_order' => 2],
            ['name' => 'Mount Lavinia', 'province' => 'Western Province', 'sort_order' => 3],
            ['name' => 'Kalutara', 'province' => 'Western Province', 'sort_order' => 4],

            // Central Province - Hill Country & Heritage
            ['name' => 'Kandy', 'province' => 'Central Province', 'sort_order' => 5],
            ['name' => 'Nuwara Eliya', 'province' => 'Central Province', 'sort_order' => 6],
            ['name' => 'Sigiriya', 'province' => 'Central Province', 'sort_order' => 7],
            ['name' => 'Dambulla', 'province' => 'Central Province', 'sort_order' => 8],
            ['name' => 'Matale', 'province' => 'Central Province', 'sort_order' => 9],
            ['name' => 'Knuckles Range', 'province' => 'Central Province', 'sort_order' => 10],

            // Southern Province - Beaches & Wildlife
            ['name' => 'Galle', 'province' => 'Southern Province', 'sort_order' => 11],
            ['name' => 'Unawatuna', 'province' => 'Southern Province', 'sort_order' => 12],
            ['name' => 'Mirissa', 'province' => 'Southern Province', 'sort_order' => 13],
            ['name' => 'Weligama', 'province' => 'Southern Province', 'sort_order' => 14],
            ['name' => 'Tangalle', 'province' => 'Southern Province', 'sort_order' => 15],
            ['name' => 'Hikkaduwa', 'province' => 'Southern Province', 'sort_order' => 16],
            ['name' => 'Bentota', 'province' => 'Southern Province', 'sort_order' => 17],
            ['name' => 'Yala National Park', 'province' => 'Southern Province', 'sort_order' => 18],
            ['name' => 'Udawalawe National Park', 'province' => 'Southern Province', 'sort_order' => 19],
            ['name' => 'Bundala National Park', 'province' => 'Southern Province', 'sort_order' => 20],
            ['name' => 'Tissamaharama', 'province' => 'Southern Province', 'sort_order' => 21],
            ['name' => 'Hambantota', 'province' => 'Southern Province', 'sort_order' => 22],

            // Uva Province - Scenic Hill Country
            ['name' => 'Ella', 'province' => 'Uva Province', 'sort_order' => 23],
            ['name' => 'Bandarawela', 'province' => 'Uva Province', 'sort_order' => 24],
            ['name' => 'Haputale', 'province' => 'Uva Province', 'sort_order' => 25],
            ['name' => 'Badulla', 'province' => 'Uva Province', 'sort_order' => 26],
            ['name' => 'Horton Plains', 'province' => 'Uva Province', 'sort_order' => 27],

            // North Central Province - Ancient Cities
            ['name' => 'Anuradhapura', 'province' => 'North Central Province', 'sort_order' => 28],
            ['name' => 'Polonnaruwa', 'province' => 'North Central Province', 'sort_order' => 29],
            ['name' => 'Mihintale', 'province' => 'North Central Province', 'sort_order' => 30],
            ['name' => 'Minneriya National Park', 'province' => 'North Central Province', 'sort_order' => 31],
            ['name' => 'Kaudulla National Park', 'province' => 'North Central Province', 'sort_order' => 32],
            ['name' => 'Habarana', 'province' => 'North Central Province', 'sort_order' => 33],
            ['name' => 'Ritigala', 'province' => 'North Central Province', 'sort_order' => 34],

            // Eastern Province - Beaches & Culture
            ['name' => 'Trincomalee', 'province' => 'Eastern Province', 'sort_order' => 35],
            ['name' => 'Arugam Bay', 'province' => 'Eastern Province', 'sort_order' => 36],
            ['name' => 'Batticaloa', 'province' => 'Eastern Province', 'sort_order' => 37],
            ['name' => 'Passikudah', 'province' => 'Eastern Province', 'sort_order' => 38],
            ['name' => 'Nilaveli', 'province' => 'Eastern Province', 'sort_order' => 39],
            ['name' => 'Pigeon Island', 'province' => 'Eastern Province', 'sort_order' => 40],

            // Northern Province
            ['name' => 'Jaffna', 'province' => 'Northern Province', 'sort_order' => 41],
            ['name' => 'Point Pedro', 'province' => 'Northern Province', 'sort_order' => 42],
            ['name' => 'Nallur', 'province' => 'Northern Province', 'sort_order' => 43],
            ['name' => 'Delft Island', 'province' => 'Northern Province', 'sort_order' => 44],
            ['name' => 'Mannar', 'province' => 'Northern Province', 'sort_order' => 45],

            // North Western Province
            ['name' => 'Chilaw', 'province' => 'North Western Province', 'sort_order' => 46],
            ['name' => 'Kurunegala', 'province' => 'North Western Province', 'sort_order' => 47],
            ['name' => 'Wilpattu National Park', 'province' => 'North Western Province', 'sort_order' => 48],
            ['name' => 'Kalpitiya', 'province' => 'North Western Province', 'sort_order' => 49],
            ['name' => 'Puttalam', 'province' => 'North Western Province', 'sort_order' => 50],

            // Sabaragamuwa Province
            ['name' => 'Ratnapura', 'province' => 'Sabaragamuwa Province', 'sort_order' => 51],
            ['name' => 'Sinharaja Forest Reserve', 'province' => 'Sabaragamuwa Province', 'sort_order' => 52],
            ['name' => 'Adams Peak (Sri Pada)', 'province' => 'Sabaragamuwa Province', 'sort_order' => 53],
            ['name' => 'Kitulgala', 'province' => 'Sabaragamuwa Province', 'sort_order' => 54],

            // Additional Popular Destinations
            ['name' => 'Pinnawala Elephant Orphanage', 'province' => 'Sabaragamuwa Province', 'sort_order' => 55],
            ['name' => 'Gal Oya National Park', 'province' => 'Eastern Province', 'sort_order' => 56],
            ['name' => 'Kumana National Park', 'province' => 'Eastern Province', 'sort_order' => 57],
            ['name' => 'Wasgamuwa National Park', 'province' => 'Central Province', 'sort_order' => 58],
            ['name' => 'Lahugala National Park', 'province' => 'Eastern Province', 'sort_order' => 59],
            ['name' => 'Lunugamvehera National Park', 'province' => 'Southern Province', 'sort_order' => 60],
        ];

        foreach ($regions as $region) {
            Region::updateOrCreate(
                ['name' => $region['name']],
                [
                    'province' => $region['province'],
                    'sort_order' => $region['sort_order'],
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('Seeded ' . count($regions) . ' Sri Lankan tourist regions.');
    }
}
