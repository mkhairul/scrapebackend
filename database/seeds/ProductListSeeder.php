<?php

use Illuminate\Database\Seeder;

class ProductListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = DB::table('category')->get();
        foreach ($categories as $cat)
        {
            $this->command->info('Retrieving products for ' . $cat->name);
            $result = shell_exec('phantomjs ' . __DIR__.'/../../scraper/productlist.js "'.$cat->url.'"');
            $products = json_decode(trim($result), true);
            
            foreach($products as $index => $product)
            {
                DB::table('product')->insert([
                    'category_id' => $cat->id,
                    'name'        => $product['name'],
                    'desc'        => $product['desc'],
                    'url'         => $product['url']
                ]);
                $this->command->info('Inserting "'.$product['name'].'" '.($index+1).'/'.count($products));
            }
        }
    }
}
