<?php

use Illuminate\Database\Seeder;

class TestShellExec extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0; $i<1000; $i++)
        {
            try{
                $ps_list = explode(PHP_EOL, trim(shell_exec('/bin/ps -ef | grep phantomjs')));
            }catch(ErrorException $e){
                $this->command->info($e->getMessage());
                return false;
            }
        }
    }
}
