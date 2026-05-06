<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearProductCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:clear-products {--all : Clear all product-related caches}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear product catalog and detail caches';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Clearing product caches...');
        
        if ($this->option('all')) {
            // Clear all cache
            Cache::flush();
            $this->info('✓ All caches cleared');
        } else {
            // Clear only product-related caches
            $patterns = [
                'katalog_products_*',
                'product_detail_*',
                'related_products_*',
            ];
            
            foreach ($patterns as $pattern) {
                // Note: This works with file cache driver
                // For Redis, you'd need to use Cache::tags()
                $keys = Cache::getStore()->getFilesystem()->files(
                    config('cache.stores.file.path')
                );
                
                foreach ($keys as $key) {
                    $keyName = basename($key, '.cache');
                    if (str_contains($keyName, str_replace('*', '', $pattern))) {
                        Cache::forget($keyName);
                    }
                }
            }
            
            $this->info('✓ Product caches cleared');
        }
        
        $this->newLine();
        $this->info('Cache cleared successfully!');
        $this->info('Next product queries will be fresh from database.');
        
        return Command::SUCCESS;
    }
}
