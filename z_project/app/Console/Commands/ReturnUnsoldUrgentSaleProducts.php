<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReturnUnsoldUrgentSaleProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'urgent-sale:return';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move unsold urgent sale products back to Near Expiry after 3 days';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
   public function handle()
{
    Log::info('urgent-sale:return started');

    $updated = DB::table('rack_stocks')
        ->where('is_on_sale', true)
        ->where('quantity', '>', 0)
        ->whereNotNull('put_on_sale_at')
        ->where('put_on_sale_at', '<=', now()->subDays(3))
        ->update([
            'is_on_sale' => false,
            'put_on_sale_at' => null,
        ]);

    Log::info("urgent-sale:return completed. Updated {$updated} products.");

    return Command::SUCCESS;
}

}
