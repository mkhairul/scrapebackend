<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Validator;

use App\ScrapeProcess;
use Storage;

class ScrapeController extends Controller
{
    public function __construct()
    {
    }
	
		public function start(){
			// Check if a process have been started
			$process_exists = ScrapeProcess::where('status', '0')->first();
			if($process_exists)
			{
				return response()->json(['message' => 'A Process exists. Clear the process to start a new process'], 500);
			}
			
			$process_id = trim(
				shell_exec(
					'php ' . __DIR__ . '/../../../artisan db:seed --class=ScrapingProcessSeeder > /dev/null 2>&1 & echo $!'
				)
			);
			$scrape = new ScrapeProcess;
			$scrape->process_id = $process_id;
			$scrape->status = 0;
			$scrape->save();
			
			return response()->json(['status' => 'ok', 'process_id' => $process_id]);
		}
	
		public function getAll(){
			$results = ScrapeProcess::get();
			return response()->json($results);
		}
}