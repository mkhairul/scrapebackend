<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ScrapingProcessSeeder extends Seeder
{
    public function run()
    {
				Model::unguard();
			
        // Get current process id
				$process_id = getmypid();
			
				DB::table('scrape_process')->where('process_id', $process_id)->update(['status_name' => 'Category']);
				$this->call(CategoryTableSeeder::class);
			
				DB::table('scrape_process')->where('process_id', $process_id)->update(['status_name' => 'Products']);
				$this->call(ProductListSeeder::class);
				
				
				DB::table('scrape_process')->where('process_id', $process_id)->update(['status_name' => 'Package']);
				$this->call(PackageTableSeeder::class);
			
				DB::table('scrape_process')->where('process_id', $process_id)->update(['status_name' => 'Removing Duplicate Products']);
				$this->call(RemoveDuplicateProducts::class);
				
				
        $exists = DB::table('scrape_process')->where('process_id', $process_id)->first();
				if($exists)
				{
					DB::table('scrape_process')->where('process_id', $process_id)->update([
							'status' => '1',
							'date_end' => date('Y-m-d H:i:s', strotime('now'))
					]);
				}
				

        Model::reguard();
    }
}
