<?php

namespace Database\Seeders;

use App\Models\Guide;
use App\Models\GuidePlan;
use Illuminate\Database\Seeder;

class TourPackageSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing guides or create if none exist
        $guides = Guide::all();

        if ($guides->isEmpty()) {
            $this->command->error('No guides found. Please create guides first.');
            return;
        }

        $packages = [
            [
                'title' => 'Sigiriya Rock Fortress & Dambulla Cave Temple',
                'description' => 'Explore the ancient wonders of Sri Lanka on this unforgettable 2-day journey. Climb the iconic Sigiriya Rock Fortress, a UNESCO World Heritage Site known as the "Lion Rock", featuring stunning frescoes and panoramic views. Visit the magnificent Dambulla Cave Temple with its ancient Buddha statues and intricate wall paintings dating back over 2,000 years.',
                'num_days' => 2,
                'num_nights' => 1,
                'pickup_location' => 'Colombo Airport/Hotel',
                'dropoff_location' => 'Colombo Airport/Hotel',
                'destinations' => ['Sigiriya', 'Dambulla', 'Habarana'],
                'trip_focus_tags' => ['Cultural', 'Historical', 'UNESCO Heritage', 'Photography'],
                'price_per_adult' => 299.00,
                'price_per_child' => 149.00,
                'max_group_size' => 8,
                'min_group_size' => 1,
                'availability_type' => 'always_available',
                'vehicle_type' => 'Air-conditioned SUV',
                'vehicle_capacity' => 6,
                'vehicle_ac' => true,
                'dietary_options' => ['Vegetarian', 'Vegan', 'Halal'],
                'inclusions' => "Private air-conditioned vehicle\nProfessional English-speaking guide\n1 night accommodation (4-star hotel)\nBreakfast and lunch\nAll entrance fees\nMineral water throughout the trip",
                'exclusions' => "International flights\nPersonal expenses\nTips and gratuities\nTravel insurance",
                'cover_photo' => 'guide_plans/sigiriya.jpg',
                'status' => 'active',
            ],
            [
                'title' => 'Mirissa Beach & Whale Watching Adventure',
                'description' => 'Experience the magic of Sri Lanka\'s southern coast with this exciting 3-day beach adventure. Enjoy world-class whale watching in Mirissa, where you can spot blue whales, sperm whales, and playful dolphins. Relax on pristine golden beaches, explore coconut palm groves, and savor fresh seafood at beachside restaurants.',
                'num_days' => 3,
                'num_nights' => 2,
                'pickup_location' => 'Colombo Airport/Hotel',
                'dropoff_location' => 'Colombo Airport/Hotel',
                'destinations' => ['Mirissa', 'Weligama', 'Galle'],
                'trip_focus_tags' => ['Beach', 'Wildlife', 'Whale Watching', 'Relaxation'],
                'price_per_adult' => 399.00,
                'price_per_child' => 199.00,
                'max_group_size' => 6,
                'min_group_size' => 2,
                'availability_type' => 'always_available',
                'vehicle_type' => 'Air-conditioned Van',
                'vehicle_capacity' => 8,
                'vehicle_ac' => true,
                'dietary_options' => ['Vegetarian', 'Seafood', 'Vegan'],
                'inclusions' => "Private air-conditioned vehicle\nProfessional guide\n2 nights beach resort accommodation\nAll meals included\nWhale watching boat tour\nSnorkeling equipment",
                'exclusions' => "International flights\nAlcoholic beverages\nPersonal expenses\nTravel insurance",
                'cover_photo' => 'guide_plans/mirissa-beach.jpg',
                'status' => 'active',
            ],
            [
                'title' => 'Ella Mountain Escape & Train Journey',
                'description' => 'Discover the breathtaking beauty of Sri Lanka\'s hill country on this 4-day mountain adventure. Experience the world-famous scenic train ride from Kandy to Ella, hike to Little Adam\'s Peak and Ella Rock, visit the iconic Nine Arch Bridge, and explore lush tea plantations. Perfect for nature lovers and adventure seekers.',
                'num_days' => 4,
                'num_nights' => 3,
                'pickup_location' => 'Kandy City/Hotel',
                'dropoff_location' => 'Ella or Colombo',
                'destinations' => ['Ella', 'Nuwara Eliya', 'Haputale'],
                'trip_focus_tags' => ['Adventure', 'Hiking', 'Train Journey', 'Nature', 'Tea Plantations'],
                'price_per_adult' => 449.00,
                'price_per_child' => 249.00,
                'max_group_size' => 6,
                'min_group_size' => 1,
                'availability_type' => 'always_available',
                'vehicle_type' => 'Air-conditioned SUV',
                'vehicle_capacity' => 5,
                'vehicle_ac' => true,
                'dietary_options' => ['Vegetarian', 'Vegan', 'Local Cuisine'],
                'inclusions' => "Private vehicle where needed\nProfessional hiking guide\n3 nights boutique hotel accommodation\nAll breakfasts and dinners\nTrain tickets (2nd class reserved)\nAll entrance fees",
                'exclusions' => "Lunches\nPersonal hiking gear\nTravel insurance\nTips",
                'cover_photo' => 'guide_plans/ella-mountains.jpg',
                'status' => 'active',
            ],
            [
                'title' => 'Kandy Cultural Heritage Tour',
                'description' => 'Immerse yourself in Sri Lanka\'s rich cultural heritage on this 2-day Kandy tour. Visit the sacred Temple of the Tooth Relic, witness traditional Kandyan dance performances, explore the Royal Botanical Gardens of Peradeniya, and discover the charm of this UNESCO World Heritage city nestled among misty hills.',
                'num_days' => 2,
                'num_nights' => 1,
                'pickup_location' => 'Colombo Airport/Hotel',
                'dropoff_location' => 'Colombo Airport/Hotel',
                'destinations' => ['Kandy', 'Peradeniya', 'Pinnawala'],
                'trip_focus_tags' => ['Cultural', 'Religious', 'UNESCO Heritage', 'Gardens'],
                'price_per_adult' => 279.00,
                'price_per_child' => 139.00,
                'max_group_size' => 10,
                'min_group_size' => 1,
                'availability_type' => 'always_available',
                'vehicle_type' => 'Air-conditioned Car',
                'vehicle_capacity' => 4,
                'vehicle_ac' => true,
                'dietary_options' => ['Vegetarian', 'Halal', 'Non-Vegetarian'],
                'inclusions' => "Private air-conditioned vehicle\nEnglish-speaking guide\n1 night hotel accommodation\nBreakfast and dinner\nTemple entrance fees\nCultural show tickets",
                'exclusions' => "Lunch\nCamera permits\nPersonal expenses\nTravel insurance",
                'cover_photo' => 'guide_plans/kandy-temple.jpg',
                'status' => 'active',
            ],
            [
                'title' => 'Yala National Park Safari Experience',
                'description' => 'Embark on an thrilling wildlife safari in Yala National Park, home to the highest density of leopards in the world. Spot elephants, sloth bears, crocodiles, and hundreds of bird species in their natural habitat. This 3-day adventure includes multiple safari drives for the best wildlife viewing opportunities.',
                'num_days' => 3,
                'num_nights' => 2,
                'pickup_location' => 'Colombo Airport/Hotel',
                'dropoff_location' => 'Colombo Airport/Hotel',
                'destinations' => ['Yala', 'Tissamaharama', 'Kataragama'],
                'trip_focus_tags' => ['Wildlife', 'Safari', 'Photography', 'Nature'],
                'price_per_adult' => 549.00,
                'price_per_child' => 299.00,
                'max_group_size' => 4,
                'min_group_size' => 2,
                'availability_type' => 'always_available',
                'vehicle_type' => 'Safari Jeep',
                'vehicle_capacity' => 6,
                'vehicle_ac' => false,
                'dietary_options' => ['Vegetarian', 'Non-Vegetarian', 'Local Cuisine'],
                'inclusions' => "Private transfer vehicle\n2 full-day safari drives\nSafari jeep with tracker\n2 nights safari lodge\nAll meals\nPark entrance fees",
                'exclusions' => "Binoculars rental\nCamera equipment\nTravel insurance\nTips for tracker",
                'cover_photo' => 'guide_plans/yala-safari.jpg',
                'status' => 'active',
            ],
            [
                'title' => 'Galle Fort & Southern Coast Explorer',
                'description' => 'Discover the colonial charm and natural beauty of Sri Lanka\'s southern coast. Explore the UNESCO-listed Galle Fort with its Dutch architecture, visit sea turtle hatcheries, relax on stunning beaches, and enjoy fresh seafood. A perfect blend of history, culture, and coastal relaxation.',
                'num_days' => 2,
                'num_nights' => 1,
                'pickup_location' => 'Colombo Airport/Hotel',
                'dropoff_location' => 'Colombo Airport/Hotel',
                'destinations' => ['Galle', 'Unawatuna', 'Hikkaduwa'],
                'trip_focus_tags' => ['Historical', 'Beach', 'Colonial Heritage', 'Relaxation'],
                'price_per_adult' => 249.00,
                'price_per_child' => 129.00,
                'max_group_size' => 8,
                'min_group_size' => 1,
                'availability_type' => 'always_available',
                'vehicle_type' => 'Air-conditioned Van',
                'vehicle_capacity' => 8,
                'vehicle_ac' => true,
                'dietary_options' => ['Vegetarian', 'Seafood', 'International'],
                'inclusions' => "Private air-conditioned vehicle\nLicensed guide\n1 night boutique hotel\nBreakfast and lunch\nGalle Fort walking tour\nTurtle hatchery visit",
                'exclusions' => "Dinner\nWater sports\nPersonal expenses\nTravel insurance",
                'cover_photo' => 'guide_plans/galle-fort.jpg',
                'status' => 'active',
            ],
            [
                'title' => 'Nuwara Eliya Tea Country Experience',
                'description' => 'Step into Sri Lanka\'s enchanting tea country, often called "Little England." Visit working tea factories, learn about tea production from leaf to cup, explore colonial-era bungalows, and enjoy the cool mountain climate. Perfect for tea lovers and those seeking a refreshing escape.',
                'num_days' => 2,
                'num_nights' => 1,
                'pickup_location' => 'Kandy or Colombo',
                'dropoff_location' => 'Kandy or Colombo',
                'destinations' => ['Nuwara Eliya', 'Ramboda', 'Hakgala'],
                'trip_focus_tags' => ['Tea Plantations', 'Nature', 'Colonial Heritage', 'Cool Climate'],
                'price_per_adult' => 229.00,
                'price_per_child' => 119.00,
                'max_group_size' => 6,
                'min_group_size' => 1,
                'availability_type' => 'always_available',
                'vehicle_type' => 'Air-conditioned SUV',
                'vehicle_capacity' => 5,
                'vehicle_ac' => true,
                'dietary_options' => ['Vegetarian', 'Western', 'Local'],
                'inclusions' => "Private vehicle\nTea factory tour\n1 night heritage hotel\nAll meals\nGarden entrance fees\nTea tasting session",
                'exclusions' => "Personal purchases\nAdditional activities\nTravel insurance",
                'cover_photo' => 'guide_plans/nuwara-eliya.jpg',
                'status' => 'active',
            ],
            [
                'title' => 'Ancient Cities & UNESCO Heritage Trail',
                'description' => 'Journey through 2,500 years of Sri Lankan history visiting the ancient capitals. Explore Anuradhapura\'s sacred bodhi tree and massive dagobas, discover Polonnaruwa\'s medieval ruins, and witness the engineering marvels of ancient hydraulic civilization. A must for history enthusiasts.',
                'num_days' => 4,
                'num_nights' => 3,
                'pickup_location' => 'Colombo Airport/Hotel',
                'dropoff_location' => 'Colombo Airport/Hotel',
                'destinations' => ['Anuradhapura', 'Polonnaruwa', 'Mihintale', 'Sigiriya'],
                'trip_focus_tags' => ['Historical', 'UNESCO Heritage', 'Archaeological', 'Religious'],
                'price_per_adult' => 599.00,
                'price_per_child' => 299.00,
                'max_group_size' => 8,
                'min_group_size' => 2,
                'availability_type' => 'always_available',
                'vehicle_type' => 'Air-conditioned Van',
                'vehicle_capacity' => 10,
                'vehicle_ac' => true,
                'dietary_options' => ['Vegetarian', 'Non-Vegetarian', 'Vegan'],
                'inclusions' => "Private vehicle\nArchaeologist guide\n3 nights hotel accommodation\nAll meals\nAll UNESCO site entrance fees\nBicycle rental at Polonnaruwa",
                'exclusions' => "Donations at temples\nCamera permits\nTravel insurance",
                'cover_photo' => 'guide_plans/anuradhapura.jpg',
                'status' => 'active',
            ],
            [
                'title' => 'Scenic Train Journey: Colombo to Ella',
                'description' => 'Experience one of the world\'s most scenic train journeys through Sri Lanka\'s breathtaking hill country. Wind through emerald tea plantations, cross dramatic bridges, pass through misty mountains, and witness stunning waterfalls. This is the ultimate way to see Sri Lanka\'s natural beauty.',
                'num_days' => 3,
                'num_nights' => 2,
                'pickup_location' => 'Colombo',
                'dropoff_location' => 'Ella',
                'destinations' => ['Kandy', 'Nanu Oya', 'Ella'],
                'trip_focus_tags' => ['Scenic', 'Train Journey', 'Nature', 'Photography'],
                'price_per_adult' => 349.00,
                'price_per_child' => 179.00,
                'max_group_size' => 6,
                'min_group_size' => 1,
                'availability_type' => 'always_available',
                'vehicle_type' => 'Train + Support Vehicle',
                'vehicle_capacity' => 6,
                'vehicle_ac' => true,
                'dietary_options' => ['Vegetarian', 'Local Cuisine'],
                'inclusions' => "Train tickets (reserved seats)\nSupport vehicle for luggage\n2 nights accommodation\nBreakfast daily\nStation transfers\nLocal guide",
                'exclusions' => "Meals on train\nPersonal expenses\nTravel insurance",
                'cover_photo' => 'guide_plans/train-ride.jpg',
                'status' => 'active',
            ],
            [
                'title' => 'Bentota Beach Resort & Water Sports',
                'description' => 'Unwind at Sri Lanka\'s premier beach destination, Bentota. Enjoy pristine beaches, thrilling water sports including jet skiing, banana boat rides, and windsurfing. Take a boat safari on the Bentota River to spot exotic birds and monitor lizards. Perfect for beach lovers and adventure seekers.',
                'num_days' => 3,
                'num_nights' => 2,
                'pickup_location' => 'Colombo Airport/Hotel',
                'dropoff_location' => 'Colombo Airport/Hotel',
                'destinations' => ['Bentota', 'Induruwa', 'Brief Garden'],
                'trip_focus_tags' => ['Beach', 'Water Sports', 'Relaxation', 'River Safari'],
                'price_per_adult' => 379.00,
                'price_per_child' => 189.00,
                'max_group_size' => 6,
                'min_group_size' => 2,
                'availability_type' => 'always_available',
                'vehicle_type' => 'Air-conditioned Van',
                'vehicle_capacity' => 8,
                'vehicle_ac' => true,
                'dietary_options' => ['All Inclusive', 'Seafood', 'International'],
                'inclusions' => "Private transfers\n2 nights beach resort\nAll meals\nRiver boat safari\n1 water sports session\nBrief Garden entrance",
                'exclusions' => "Additional water sports\nAlcoholic drinks\nSpa treatments\nTravel insurance",
                'cover_photo' => 'guide_plans/bentota-beach.jpg',
                'status' => 'active',
            ],
        ];

        foreach ($packages as $index => $packageData) {
            // Assign to guides in rotation
            $guide = $guides[$index % $guides->count()];
            $packageData['guide_id'] = $guide->id;

            // Check if package already exists
            $exists = GuidePlan::where('title', $packageData['title'])
                               ->where('guide_id', $guide->id)
                               ->exists();

            if (!$exists) {
                GuidePlan::create($packageData);
                $this->command->info("Created: {$packageData['title']}");
            } else {
                $this->command->warn("Skipped (exists): {$packageData['title']}");
            }
        }

        $this->command->info('Tour packages seeding completed!');
    }
}
