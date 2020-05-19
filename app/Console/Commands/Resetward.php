<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Auth;
date_default_timezone_set("Asia/Kolkata");
class Resetward extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:resetward';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
           // $s = [6,2,7];
           //          $users = DB::table('users')->get();
           //         $date=date('Y-m-d');
           //          $t = [];
           //        foreach ($users as $user) {
                      
                          
                            
           //               $s =   DB::table('ward_assignments')->where('updated_at','LIKE',$date.'%')->where('time','<=',date('H:i:s'))->where('user_id',$user->id)->update(['subward_id'=>NULL]);
                            
                    
                        
           //        }

             DB::table('ward_assignments')->where('user_id',14)->delete(); 
                echo "run schedle";
    }
}
