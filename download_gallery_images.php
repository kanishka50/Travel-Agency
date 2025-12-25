<?php
/**
 * Script to download gallery images for tour packages
 * Uses free Unsplash photos via Lorem Picsum or direct downloads
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

$galleryDir = storage_path('app/public/guide_plans/gallery');
if (!is_dir($galleryDir)) {
    mkdir($galleryDir, 0755, true);
}

// Tour packages with their themed image keywords
$tourPackages = [
    3 => ['name' => 'Sigiriya', 'keywords' => ['ancient-fortress', 'rock-climbing', 'sri-lanka-ruins', 'dambulla-cave', 'buddhist-temple']],
    4 => ['name' => 'Mirissa', 'keywords' => ['whale-watching', 'tropical-beach', 'ocean-sunset', 'fishing-boat', 'palm-trees']],
    5 => ['name' => 'Ella', 'keywords' => ['mountain-train', 'tea-plantation', 'nine-arch-bridge', 'green-hills', 'waterfall']],
    6 => ['name' => 'Kandy', 'keywords' => ['temple-tooth', 'cultural-dance', 'lake-view', 'botanical-garden', 'elephant']],
    7 => ['name' => 'Yala', 'keywords' => ['leopard-safari', 'wild-elephant', 'safari-jeep', 'peacock', 'crocodile']],
    8 => ['name' => 'Galle', 'keywords' => ['dutch-fort', 'lighthouse', 'colonial-street', 'turtle-beach', 'stilt-fishermen']],
    9 => ['name' => 'NuwaraEliya', 'keywords' => ['tea-factory', 'hill-station', 'english-garden', 'strawberry-farm', 'waterfall-view']],
    10 => ['name' => 'Anuradhapura', 'keywords' => ['ancient-dagoba', 'sacred-tree', 'polonnaruwa', 'buddha-statue', 'ruins-temple']],
    11 => ['name' => 'TrainJourney', 'keywords' => ['blue-train', 'scenic-rail', 'mountain-view', 'bridge-crossing', 'countryside']],
    12 => ['name' => 'Bentota', 'keywords' => ['water-sports', 'river-cruise', 'beach-resort', 'jet-ski', 'mangrove']],
];

// Unsplash image IDs for Sri Lanka travel themes
$unsplashImages = [
    'sigiriya' => [
        'https://images.unsplash.com/photo-1586185026629-0f7eb6e35a6b?w=800&q=80', // Sigiriya rock
        'https://images.unsplash.com/photo-1588598198215-d9c25f11f9fa?w=800&q=80', // Sri Lanka landscape
        'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800&q=80', // Temple
        'https://images.unsplash.com/photo-1552465011-b4e21bf6e79a?w=800&q=80', // Ancient ruins
        'https://images.unsplash.com/photo-1546708770-599a28468ec1?w=800&q=80', // Buddhist temple
    ],
    'beach' => [
        'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&q=80', // Tropical beach
        'https://images.unsplash.com/photo-1519046904884-53103b34b206?w=800&q=80', // Beach sunset
        'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800&q=80', // Ocean waves
        'https://images.unsplash.com/photo-1516370873344-fb769e0e9d52?w=800&q=80', // Palm trees beach
        'https://images.unsplash.com/photo-1583212292454-1fe6229603b7?w=800&q=80', // Whale watching
    ],
    'mountains' => [
        'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=800&q=80', // Mountains
        'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?w=800&q=80', // Foggy mountains
        'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&q=80', // Mountain sunrise
        'https://images.unsplash.com/photo-1454496522488-7a8e488e8606?w=800&q=80', // Mountain peaks
        'https://images.unsplash.com/photo-1501785888041-af3ef285b470?w=800&q=80', // Scenic landscape
    ],
    'cultural' => [
        'https://images.unsplash.com/photo-1545579133-99bb5ab189bd?w=800&q=80', // Temple
        'https://images.unsplash.com/photo-1548013146-72479768bada?w=800&q=80', // Cultural dance
        'https://images.unsplash.com/photo-1558862107-d49ef2a04d72?w=800&q=80', // Buddha statue
        'https://images.unsplash.com/photo-1609137144813-7d9921338f24?w=800&q=80', // Elephant
        'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800&q=80', // Temple interior
    ],
    'safari' => [
        'https://images.unsplash.com/photo-1516426122078-c23e76319801?w=800&q=80', // Leopard
        'https://images.unsplash.com/photo-1557050543-4d5f4e07ef46?w=800&q=80', // Elephant
        'https://images.unsplash.com/photo-1547970810-dc1eac37d174?w=800&q=80', // Safari jeep
        'https://images.unsplash.com/photo-1474511320723-9a56873571b7?w=800&q=80', // Peacock
        'https://images.unsplash.com/photo-1504173010664-32509aeebb62?w=800&q=80', // Safari landscape
    ],
    'colonial' => [
        'https://images.unsplash.com/photo-1552733407-5d5c46c3bb3b?w=800&q=80', // Fort
        'https://images.unsplash.com/photo-1518509562904-e7ef99cdcc86?w=800&q=80', // Lighthouse
        'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&q=80', // Colonial street
        'https://images.unsplash.com/photo-1544551763-77932f494d23?w=800&q=80', // Beach town
        'https://images.unsplash.com/photo-1559827291-72ee739d0d9a?w=800&q=80', // Historic building
    ],
    'tea' => [
        'https://images.unsplash.com/photo-1566305977571-5666677c6e8a?w=800&q=80', // Tea plantation
        'https://images.unsplash.com/photo-1587049352851-8d4e89133924?w=800&q=80', // Tea picking
        'https://images.unsplash.com/photo-1571934811356-5cc061b6821f?w=800&q=80', // Green hills
        'https://images.unsplash.com/photo-1558642452-9d2a7deb7f62?w=800&q=80', // Garden
        'https://images.unsplash.com/photo-1467894502499-b9d2d2ccc36a?w=800&q=80', // Waterfall
    ],
    'ancient' => [
        'https://images.unsplash.com/photo-1552465011-b4e21bf6e79a?w=800&q=80', // Ancient temple
        'https://images.unsplash.com/photo-1546708770-599a28468ec1?w=800&q=80', // Dagoba
        'https://images.unsplash.com/photo-1591023036897-f9c55d19ef8f?w=800&q=80', // Buddha
        'https://images.unsplash.com/photo-1590123297886-403c99df84b7?w=800&q=80', // Ruins
        'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800&q=80', // Sacred site
    ],
    'train' => [
        'https://images.unsplash.com/photo-1474487548417-781cb71495f3?w=800&q=80', // Train journey
        'https://images.unsplash.com/photo-1532105956626-9569c03602f6?w=800&q=80', // Railway bridge
        'https://images.unsplash.com/photo-1507499739999-097706ad8914?w=800&q=80', // Scenic train
        'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=800&q=80', // Train window
        'https://images.unsplash.com/photo-1501785888041-af3ef285b470?w=800&q=80', // Mountain railway
    ],
    'watersports' => [
        'https://images.unsplash.com/photo-1530053969600-caed2596d242?w=800&q=80', // Water sports
        'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800&q=80', // Ocean
        'https://images.unsplash.com/photo-1534256958597-7fe685cbd745?w=800&q=80', // Jet ski
        'https://images.unsplash.com/photo-1506197603052-3cc9c3a201bd?w=800&q=80', // Boat
        'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&q=80', // Beach
    ],
];

// Mapping of tour packages to image categories
$tourCategories = [
    3 => 'sigiriya',    // Sigiriya Rock Fortress
    4 => 'beach',       // Mirissa Beach
    5 => 'mountains',   // Ella Mountains
    6 => 'cultural',    // Kandy Cultural
    7 => 'safari',      // Yala Safari
    8 => 'colonial',    // Galle Fort
    9 => 'tea',         // Nuwara Eliya Tea
    10 => 'ancient',    // Anuradhapura Ancient Cities
    11 => 'train',      // Train Journey
    12 => 'watersports', // Bentota Beach
];

echo "Starting gallery image download...\n";

$insertedCount = 0;

foreach ($tourCategories as $planId => $category) {
    echo "Processing Tour Package ID: $planId ($category)\n";

    $images = $unsplashImages[$category] ?? [];
    $displayOrder = 1;

    foreach ($images as $index => $imageUrl) {
        $filename = "{$category}_{$planId}_{$index}.jpg";
        $savePath = $galleryDir . '/' . $filename;
        $dbPath = "guide_plans/gallery/{$filename}";

        // Download image
        echo "  Downloading: $filename\n";

        $ch = curl_init($imageUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $imageData = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode == 200 && $imageData) {
            file_put_contents($savePath, $imageData);
            echo "    Saved: $filename\n";

            // Insert into database
            DB::table('guide_plan_photos')->insert([
                'guide_plan_id' => $planId,
                'photo_path' => $dbPath,
                'display_order' => $displayOrder,
                'uploaded_at' => now(),
            ]);

            $insertedCount++;
            $displayOrder++;
        } else {
            echo "    Failed to download (HTTP $httpCode)\n";
        }

        // Small delay to avoid rate limiting
        usleep(200000);
    }
}

echo "\n================================\n";
echo "Gallery images download complete!\n";
echo "Total images inserted: $insertedCount\n";
echo "================================\n";
