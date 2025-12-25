<?php
/**
 * Add final images for packages with fewer photos
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$galleryDir = storage_path('app/public/guide_plans/gallery');

// Extra images for sigiriya (3) and ancient (10)
$extraImages = [
    ['plan_id' => 3, 'url' => 'https://images.unsplash.com/photo-1544735716-392fe2489ffa?w=800&q=80', 'name' => 'sigiriya_3_5.jpg'],
    ['plan_id' => 10, 'url' => 'https://images.unsplash.com/photo-1544735716-392fe2489ffa?w=800&q=80', 'name' => 'ancient_10_5.jpg'],
];

$successCount = 0;

foreach ($extraImages as $img) {
    $savePath = $galleryDir . '/' . $img['name'];
    $dbPath = "guide_plans/gallery/{$img['name']}";

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

        $maxOrder = DB::table('guide_plan_photos')
            ->where('guide_plan_id', $img['plan_id'])
            ->max('display_order') ?? 0;

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
}

// Fix display order for all plans
echo "\nFixing display order...\n";

$plans = DB::table('guide_plan_photos')
    ->select('guide_plan_id')
    ->distinct()
    ->get();

foreach ($plans as $plan) {
    $photos = DB::table('guide_plan_photos')
        ->where('guide_plan_id', $plan->guide_plan_id)
        ->orderBy('id')
        ->get();

    $order = 1;
    foreach ($photos as $photo) {
        DB::table('guide_plan_photos')
            ->where('id', $photo->id)
            ->update(['display_order' => $order]);
        $order++;
    }
}

echo "Done! Added $successCount extra images.\n";

// Final count
$total = DB::table('guide_plan_photos')->count();
echo "Total gallery photos: $total\n";
