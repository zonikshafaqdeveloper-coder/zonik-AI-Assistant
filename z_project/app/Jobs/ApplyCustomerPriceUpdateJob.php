<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\CustomerPrice;

class ApplyCustomerPriceUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $log;

    public function __construct($log)
    {
        $this->log = $log;
    }

    public function handle()
    {
        CustomerPrice::where('product_id', $this->log->product_id)
            ->chunkById(500, function ($rows) {

                foreach ($rows as $row) {

                    $row->increment('product_price', $this->log->difference);

                   
                    \Log::info('Customer price updated', [
                        'customer_price_id' => $row->id,
                        'old_price' => $row->product_price,
                        'difference' => $this->log->difference
                    ]);
                }
            });
    }
}
