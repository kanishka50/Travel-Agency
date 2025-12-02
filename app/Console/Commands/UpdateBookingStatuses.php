<?php

namespace App\Console\Commands;

use App\Mail\ReviewRequest;
use App\Mail\TourReminder;
use App\Mail\TourStarting;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UpdateBookingStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically update booking statuses based on dates (confirmed → upcoming → ongoing → completed)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting booking status update...');

        $today = Carbon::today();
        $sevenDaysFromNow = Carbon::today()->addDays(7);

        $updatedCount = 0;

        // 1. CONFIRMED → UPCOMING (7 days before start date)
        $this->info('Checking for bookings becoming upcoming...');
        $upcomingBookings = Booking::where('status', 'confirmed')
            ->whereDate('start_date', '=', $sevenDaysFromNow)
            ->with(['tourist.user', 'guide.user', 'guidePlan'])
            ->get();

        foreach ($upcomingBookings as $booking) {
            $booking->update(['status' => 'upcoming']);

            Log::info('Booking status updated to upcoming', [
                'booking_id' => $booking->id,
                'booking_number' => $booking->booking_number,
                'start_date' => $booking->start_date->format('Y-m-d'),
            ]);

            // Send reminder emails to tourist and guide
            try {
                Mail::to($booking->tourist->user->email)->send(new TourReminder($booking, 'tourist'));
                Mail::to($booking->guide->user->email)->send(new TourReminder($booking, 'guide'));
                Log::info('Tour reminder emails sent', ['booking_id' => $booking->id]);
            } catch (\Exception $e) {
                Log::error('Failed to send tour reminder emails', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage(),
                ]);
            }

            $this->line("  ✓ Booking #{$booking->booking_number} → upcoming (starts {$booking->start_date->format('M j')})");
            $updatedCount++;
        }

        // 2. CONFIRMED/UPCOMING → ONGOING (on start date)
        $this->info('Checking for bookings starting today...');
        $ongoingBookings = Booking::whereIn('status', ['confirmed', 'upcoming'])
            ->whereDate('start_date', '=', $today)
            ->with(['tourist.user', 'guide.user', 'guidePlan'])
            ->get();

        foreach ($ongoingBookings as $booking) {
            $booking->update(['status' => 'ongoing']);

            Log::info('Booking status updated to ongoing', [
                'booking_id' => $booking->id,
                'booking_number' => $booking->booking_number,
                'start_date' => $booking->start_date->format('Y-m-d'),
            ]);

            // Send "tour starting" emails to tourist and guide
            try {
                Mail::to($booking->tourist->user->email)->send(new TourStarting($booking, 'tourist'));
                Mail::to($booking->guide->user->email)->send(new TourStarting($booking, 'guide'));
                Log::info('Tour starting emails sent', ['booking_id' => $booking->id]);
            } catch (\Exception $e) {
                Log::error('Failed to send tour starting emails', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage(),
                ]);
            }

            $this->line("  ✓ Booking #{$booking->booking_number} → ongoing (tour starts today!)");
            $updatedCount++;
        }

        // 3. ONGOING → COMPLETED (day after end date)
        $this->info('Checking for completed bookings...');
        $completedBookings = Booking::where('status', 'ongoing')
            ->whereDate('end_date', '<', $today)
            ->with(['tourist.user', 'guide.user', 'guidePlan'])
            ->get();

        foreach ($completedBookings as $booking) {
            $booking->update(['status' => 'completed']);

            // Update guide statistics
            $guide = $booking->guide;
            $guide->increment('total_bookings');

            Log::info('Booking status updated to completed', [
                'booking_id' => $booking->id,
                'booking_number' => $booking->booking_number,
                'end_date' => $booking->end_date->format('Y-m-d'),
                'guide_total_bookings' => $guide->total_bookings,
            ]);

            // Send review request email to tourist
            try {
                Mail::to($booking->tourist->user->email)->send(new ReviewRequest($booking));
                Log::info('Review request email sent', ['booking_id' => $booking->id]);
            } catch (\Exception $e) {
                Log::error('Failed to send review request email', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage(),
                ]);
            }

            $this->line("  ✓ Booking #{$booking->booking_number} → completed (ended {$booking->end_date->format('M j')})");
            $updatedCount++;
        }

        // Summary
        if ($updatedCount === 0) {
            $this->info('No bookings needed status updates.');
        } else {
            $this->info("Successfully updated {$updatedCount} booking(s).");
        }

        Log::info('Booking status update completed', [
            'updated_count' => $updatedCount,
            'run_at' => now()->toDateTimeString(),
        ]);

        return Command::SUCCESS;
    }
}
