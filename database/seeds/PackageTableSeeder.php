<?php

use Illuminate\Database\Seeder;

class PackageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = DB::table('product')->where('article_id', '')->where('problem', 0)->orderByRaw("RAND()")->get();
        foreach ($products as $product)
        {
            $tmp = DB::table('product')->where('id', $product->id)->where('article_id', '')->first();
            if(!$tmp){ $this->command->info('Skipping ID: ' . $product->id); continue; }
                      
            $this->command->info('Retrieving product detail, ' . $product->name . ', ' . $product->id);
            $result = shell_exec('phantomjs ' . __DIR__.'/../../scraper/productdetail.js "'.$product->url.'"');
            $details = json_decode(trim($result), true);
                      
            $tmp = DB::table('product')->where('id', $product->id)->where('article_id', '')->first();
            if(!$tmp){ $this->command->info('Skipping ID: ' . $product->id); continue; }
            
            if(!is_array($details))
            {
                DB::table('product')
                    ->where('id', $product->id)
                    ->update(['problem' => 1]);
                continue;
            }
            
            if(array_key_exists('status', $details))
            {
                if($details['status'] == 'error')
                {
                    DB::table('product')
                        ->where('id', $product->id)
                        ->update(['problem' => 1]);
                    continue;
                }
            }
            
            DB::table('product')
                ->where('id', $product->id)
                ->update(['article_id' => $details['article_id']]);
            
            foreach($details['package'] as $package)
            {
                DB::table('package')->insert([
                    'product_id'    => $product->id,
                    'article_id'    => $package['article_id'],
                    'width'         => $package['width'],
                    'height'        => $package['height'],
                    'length'        => $package['length'],
                    'weight'        => $package['weight'],
                    'diameter'      => $package['diameter'],
                    'total'         => $package['total']
                ]);
            }
            $this->command->info('Updating "'.$product->name);
        }
    }
}
