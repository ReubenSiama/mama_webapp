<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\FieldLoginApprove;
use Illuminate\Support\Facades\Mail;
use App\EmployeeDetails;
date_default_timezone_set("Asia/Kolkata");

class cronEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SendEmail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Email for Every day';

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
              $email =  EmployeeDetails::where('official_email','!=',NULL)->pluck('official_email');

                 
                 for($i=0;$i<sizeof($email);$i++){

                  Mail::to($email[$i])->send(new FieldLoginApprove());
                 }

    }
}
