<?php

use Illuminate\Database\Seeder;

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
        
        foreach($categories as $index => $cat)
        {
            DB::table('category')->insert([
                'name' => $cat['name'],
                'url' => $cat['url']
            ]);
            $this->command->info('Inserting "'.$cat['name'].'" '.($index+1).'/'.count($categories));
        }
        
        $this->command->info('Total Categories: ' . count($categories));
    }
}
