<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Order;
use Illuminate\Support\Facades\Log;


class NightlyKfs implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Running Nightly KFS Job');

        // First let's find all orders that are in invoice, that have not yet been sent to KFS
        $orders = Order::with(['items', 'facility'])->where('status', 'invoice')->get();

        // Let's start creating line items.
        $kfs_line_items = collect();

        foreach ($orders as $order) {
            
            foreach ( $order->items as $item ) {
                Log::info($item->kfsDebitLine());
            }
        }
    }
}
