<?php

namespace App\Console\Commands;

use App\Models\GuidePlan;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeactivateExpiredPlans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plans:deactivate-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically deactivate seasonal guide plans that have passed their end date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired seasonal plans...');

        $today = Carbon::today();

        // Find all active seasonal plans that have expired
        $expiredPlans = GuidePlan::where('status', 'active')
            ->where('availability_type', 'date_range') // Only seasonal plans
            ->whereDate('available_end_date', '<', $today)
            ->with('guide.user')
            ->get();

        if ($expiredPlans->isEmpty()) {
            $this->info('No expired plans found.');
            Log::info('Plans deactivation check completed - no expired plans', [
                'run_at' => now()->toDateTimeString(),
            ]);
            return Command::SUCCESS;
        }

        $deactivatedCount = 0;

        foreach ($expiredPlans as $plan) {
            // Update status to inactive
            $plan->update(['status' => 'inactive']);

            Log::info('Seasonal plan automatically deactivated', [
                'plan_id' => $plan->id,
                'plan_title' => $plan->title,
                'guide_id' => $plan->guide_id,
                'guide_name' => $plan->guide->full_name,
                'end_date' => $plan->available_end_date->format('Y-m-d'),
                'days_expired' => $today->diffInDays($plan->available_end_date),
            ]);

            // TODO: In Phase 5.2, optionally send email to guide
            // Mail::to($plan->guide->user->email)->send(new PlanExpiredNotification($plan));

            $this->line("  ✓ Plan #{$plan->id}: '{$plan->title}' → inactive (expired {$plan->available_end_date->format('M j, Y')})");
            $deactivatedCount++;
        }

        $this->info("Successfully deactivated {$deactivatedCount} expired plan(s).");

        Log::info('Plans deactivation completed', [
            'deactivated_count' => $deactivatedCount,
            'run_at' => now()->toDateTimeString(),
        ]);

        return Command::SUCCESS;
    }
}
