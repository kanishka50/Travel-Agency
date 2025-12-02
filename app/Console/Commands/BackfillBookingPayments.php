<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\BookingPayment;
use Illuminate\Console\Command;

class BackfillBookingPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:backfill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill booking payment records for existing paid bookings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting backfill of booking payment records...');

        // Find all paid bookings that don't have payment records
        $paidBookings = Booking::whereNotNull('paid_at')
            ->whereDoesntHave('payment')
            ->get();

        if ($paidBookings->isEmpty()) {
            $this->info('No bookings found that need payment records.');
            return Command::SUCCESS;
        }

        $this->info("Found {$paidBookings->count()} paid bookings without payment records.");

        $progressBar = $this->output->createProgressBar($paidBookings->count());
        $progressBar->start();

        $created = 0;
        $failed = 0;

        foreach ($paidBookings as $booking) {
            try {
                BookingPayment::create([
                    'booking_id' => $booking->id,
                    'total_amount' => $booking->total_amount,
                    'platform_fee' => $booking->platform_fee,
                    'guide_payout' => $booking->guide_payout,
                    'guide_paid' => false, // Assume guides haven't been paid yet
                ]);
                $created++;
            } catch (\Exception $e) {
                $this->error("\nFailed to create payment record for booking {$booking->booking_number}: {$e->getMessage()}");
                $failed++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info("Backfill completed!");
        $this->table(
            ['Status', 'Count'],
            [
                ['Created', $created],
                ['Failed', $failed],
                ['Total', $paidBookings->count()],
            ]
        );

        return Command::SUCCESS;
    }
}
