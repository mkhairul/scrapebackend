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
						$product_exists = 0;
						$product_new = 0;
            
            foreach($products as $index => $product)
            {
                // Check if product exists
                // If it exists, just add another category to it
                $exists = DB::table('product')
													->where('category_id', $cat->id)
													->where('name', $product['name'])
													->first();
								if($exists){
									$this->command->info('Product Exists: ' . $exists->name);
									$product_exists += 1;
								}else{
									$this->command->info('New Product: ' . $product['name']);
									$product_new += 1;
									/*
									DB::table('product')->insert([
											'category_id' => $cat->id,
											'name'        => $product['name'],
											'desc'        => $product['desc'],
											'url'         => $product['url']
									]);
									*/
									$this->command->info('Inserting "'.$product['name'].'" '.($index+1).'/'.count($products));
								}
            }
        }
    }
}