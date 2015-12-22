<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $url = 'http://www.ikea.com/my/en/catalog/allproducts/department/';
        
        $this->command->info('Retrieving category list');
        $result = shell_exec('phantomjs ' . __DIR__.'/../../scraper/categories.js "'.$url.'"');
        $categories = json_decode(trim($result), true);
				$cat_exists = 0;
				$cat_new = 0;
				$results = [];
        
        foreach($categories as $index => $cat)
        {
						// Check if the category exists
						$exists = DB::table('category')->where('name', $cat['name'])->first();
						if($exists){
							$this->command->info('Category Exists: ' . $exists->name);
							$results['exists'][] = $exists->name; 
							$cat_exists += 1;
						}
						else
						{
							$this->command->info('Category New: ' . $cat['name']);
							$results['new'][] = $cat['name']; 
							$cat_new += 1;
							/*
							DB::table('category')->insert([
									'name' => $cat['name'],
									'url' => $cat['url']
							]);
							$this->command->info('Inserting "'.$cat['name'].'" '.($index+1).'/'.count($categories));
							*/
						}
						
        }
				$results['new_cat'] = $cat_new;
				$results['exists_cat'] = $cat_exists;
				$results['total'] = count($categories);
			
				$this->command->info('New Categories: ' . $cat_new);
        $this->command->info('Existing Categories: ' . $cat_exists);
        $this->command->info('Total Categories: ' . count($categories));
			
				Log::info('Category Seeder: ' . print_r($results, true));
    }
}
