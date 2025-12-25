<?php
/**
 * Script to fix missing gallery images
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$galleryDir = storage_path('app/public/guide_plans/gallery');

// Alternative images for failed downloads
$alternativeImages = [
    // Sigiriya - IDs 3
    ['plan_id' => 3, 'url' => 'https://images.unsplash.com/photo-1580977276076-ae4b8c219b8e?w=800&q=80', 'name' => 'sigiriya_3_0.jpg'],
    ['plan_id' => 3, 'url' => 'https://images.unsplash.com/photo-1571115764595-644a1f56a55c?w=800&q=80', 'name' => 'sigiriya_3_1.jpg'],
    ['plan_id' => 3, 'url' => 'https://images.unsplash.com/photo-1576675466969-38eeae4b41f6?w=800&q=80', 'name' => 'sigiriya_3_4.jpg'],

    // Beach - ID 4
    ['plan_id' => 4, 'url' => 'https://images.unsplash.com/photo-1559494007-9f5847c49d94?w=800&q=80', 'name' => 'beach_4_3.jpg'],

    // Safari - ID 7
    ['plan_id' => 7, 'url' => 'https://images.unsplash.com/photo-1549366021-9f761d450615?w=800&q=80', 'name' => 'safari_7_3.jpg'],

    // Colonial - ID 8
    ['plan_id' => 8, 'url' => 'https://images.unsplash.com/photo-1565967511849-76a60a516170?w=800&q=80', 'name' => 'colonial_8_3.jpg'],

    // Tea - ID 9
    ['plan_id' => 9, 'url' => 'https://images.unsplash.com/photo-1564890369478-c89ca6d9cde9?w=800&q=80', 'name' => 'tea_9_0.jpg'],
    ['plan_id' => 9, 'url' => 'https://images.unsplash.com/photo-1555685812-4b943f1cb0eb?w=800&q=80', 'name' => 'tea_9_4.jpg'],

    // Ancient - ID 10
    ['plan_id' => 10, 'url' => 'https://images.unsplash.com/photo-1564564321837-a57b89cf82f9?w=800&q=80', 'name' => 'ancient_10_1.jpg'],
    ['plan_id' => 10, 'url' => 'https://images.unsplash.com/photo-1609137144813-7d9921338f24?w=800&q=80', 'name' => 'ancient_10_2.jpg'],
    ['plan_id' => 10, 'url' => 'https://images.unsplash.com/photo-1570168007204-dfb528c6958f?w=800&q=80', 'name' => 'ancient_10_3.jpg'],
];

echo "Downloading missing images...\n";

$successCount = 0;

foreach ($alternativeImages as $img) {
    $savePath = $galleryDir . '/' . $img['name'];
    $dbPath = "guide_plans/gallery/{$img['name']}";

    // Check if file already exists
    if (file_exists($savePath) && filesize($savePath) > 1000) {
        echo "  Skipping (exists): {$img['name']}\n";
        continue;
    }

    echo "  Downloading: {$img['name']}\n";

    $ch = curl_init($img['url']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $imageData = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 200 && $imageData && strlen($imageData) > 1000) {
        file_put_contents($savePath, $imageData);
        echo "    Saved: {$img['name']}\n";

        // Get the next display order for this plan
        $maxOrder = DB::table('guide_plan_photos')
            ->where('guide_plan_id', $img['plan_id'])
            ->max('display_order') ?? 0;

        // Insert into database
        DB::table('guide_plan_photos')->insert([
            'guide_plan_id' => $img['plan_id'],
            'photo_path' => $dbPath,
            'display_order' => $maxOrder + 1,
            'uploaded_at' => now(),
        ]);

        $successCount++;
    } else {
        echo "    Failed (HTTP $httpCode)\n";
    }

    usleep(200000);
}

echo "\n================================\n";
echo "Fixed $successCount missing images\n";
echo "================================\n";

// Verify total count
$totalPhotos = DB::table('guide_plan_photos')->count();
echo "Total gallery photos in database: $totalPhotos\n";
