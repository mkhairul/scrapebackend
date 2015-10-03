<?php

use Illuminate\Database\Seeder;

class PackageTableSeeder extends Seeder
{
    
    
    protected $queue = [];
    protected $scraper_dir = __DIR__.'/../../scraper/';
    protected $scraper_data_dir = __DIR__.'/../../scraper/data/';
    protected $queue_limit = 6;
    protected $sleep = 2;
    protected $process_limit = 5;
    protected $process_limit_ut = 5 * 60; // 5 minutes;
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function queueTotal()
    {
        return count($this->queue);
    }
    
    public function addScraper($id, $url)
    {
        $tmp = DB::table('product')->where('id', $id)->where('article_id', '')->first();
        if(!$tmp){ $this->command->info('Skipping ID: ' . $id); continue; }
        
        $this->command->info('Spawning scraper for ' . $id);
        
        $this->queue[] = ['product_id'  => $id, 
                          'filename'    => $id,
                          'proc_id'     => trim(shell_exec('phantomjs ' .$this->scraper_dir. 'productdetail.js "'.$url.'" > '.$this->scraper_dir.'data/'.$id.' 2>&1 & echo $!'))];
    }
    
    public function updateProcess()
    {
        $ps_list = explode(PHP_EOL, trim(shell_exec('ps -ef | grep phantomjs')));
        foreach($ps_list as $process)
        {
            $ps_segment = preg_split('/\s+/', $process);
            $elapsed = explode(':', $ps_segment[6]);
            if($elapsed[1] >= $this->process_limit)
            {
                $this->command->info('killing process');
                shell_exec('kill -9 ' . $ps_segment[1]);
            }
        }
    }
    
    public function updateQueue()
    {
        foreach($this->queue as $index => $row)
        {
            $this->updateProcess();
            
            if(!file_exists('/proc/'.$row['proc_id']) && file_exists($this->scraper_data_dir . $row['filename']))
            {
            
                $content = file_get_contents($this->scraper_data_dir . $row['filename']);
                $details = json_decode(trim($content), true);

                $tmp = DB::table('product')->where('id', $row['product_id'])->where('article_id', '')->first();
                if(!$tmp){ $this->command->info('Skipping ID: ' . $row['product_id']); continue; }

                if(!is_array($details))
                {
                    DB::table('product')
                        ->where('id', $row['product_id'])
                        ->update(['problem' => 1, 'problem_log' => $content]);
                    $this->command->info('Data is not array, ' . $row['filename']);
                    unlink($this->scraper_data_dir.$row['filename']);
                    unset($this->queue[$index]);
                    continue;
                }

                if(array_key_exists('status', $details))
                {
                    if($details['status'] == 'error')
                    {
                        DB::table('product')
                            ->where('id', $row['product_id'])
                            ->update(['problem' => 1, 'problem_log' => $content]);
                        $this->command->info('status: error');
                        unlink($this->scraper_data_dir.$row['filename']);
                        unset($this->queue[$index]);
                        continue;
                    }
                }

                DB::table('product')
                    ->where('id', $row['product_id'])
                    ->update(['article_id' => $details['article_id'],
                              'price'      => $details['price']
                             ]);
                
                // Insert the product image
                DB::table('misc_data')->insert([
                        'parent_id' => $row['product_id'],
                        'type'      => 'product',
                        'name'      => 'main_img',
                        'data'      => $details['main_img']
                    ]);
                    

                foreach($details['package'] as $package)
                {
                    DB::table('package')->insert([
                        'product_id'    => $row['product_id'],
                        'article_id'    => $package['article_id'],
                        'width'         => $package['width'],
                        'height'        => $package['height'],
                        'length'        => $package['length'],
                        'weight'        => $package['weight'],
                        'diameter'      => $package['diameter'],
                        'total'         => $package['total']
                    ]);
                }
                $this->command->info('Updating '.$row['product_id']);
                $this->command->info('Deleting temp file');
                unlink($this->scraper_data_dir.$row['filename']);
                unset($this->queue[$index]);
            }
        }
    }
    
    public function run()
    {
        /*
         - loop through products
           - addScraper($product)
           - while(scraperQueue() >= $processLimit)
             - updateQueue();
             - wait(5);
         - updateQueue();     
         */
        $products = DB::table('product')->where('article_id', '')->get();
        foreach ($products as $product)
        { 
            $this->addScraper($product->id, $product->url);
            while($this->queueTotal() >= $this->queue_limit)
            {
                $this->updateQueue();
                sleep($this->sleep);
            }
        }
        
        // Finish off any other processes.
        while($this->queueTotal() > 0)
        {
            $this->updateQueue();
            sleep($this->sleep);
        }
    }
}
