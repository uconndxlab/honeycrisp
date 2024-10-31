<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Order;
use App\Models\Export;
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

        // First let's find all orders that are from KFS users and have a KFS account facility

    
        $orders = Order::with(['items', 'facility'])
            ->where('status', 'invoice')
            ->whereHas('facility', function($query) {
                $query->where('account_type', 'kfs');
            })
            ->whereHas('paymentAccount', function($query) {
                $query->where('account_type', 'kfs');
            })
            ->where('price_group', '=', 'internal')
            ->get();

        // Let's start creating line items.
        $kfs_line_items = collect();

        $counter = 0;

        foreach ($orders as $order) {  
            foreach ( $order->items as $item ) {
                $kfs_line_items->push($item->kfsDebitLine($counter));
                $kfs_line_items->push($item->kfsCreditLine($counter));
                $counter++;
            }
        }

        // Save line items to a text file
        $fileName = 'kfs-' . now()->format('Y-m-d-H-i-s') . '.data';
        $filePath = storage_path('app/exports/' . $fileName);

        if ( !file_exists($filePath) && $kfs_line_items->isNotEmpty() ) {
            file_put_contents($filePath, $kfs_line_items->implode("\n"));

            Log::info('KFS file created and saved to ' . $filePath);

            // Save the file path to the Export model
            $export = Export::create([
                'path' => $filePath,
                'type' => 'kfs'
            ]);

            Order::whereIn('id', $orders->pluck('id'))->update([
                'kfs_export_id' => $export->id,
                'status' => 'sent_to_kfs'
            ]);
        } else {
            Log::info('No KFS file created.');
        }

        Log::info('Nightly KFS Job completed.');
    }
}
