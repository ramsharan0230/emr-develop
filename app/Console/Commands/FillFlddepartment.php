<?php

namespace App\Console\Commands;

use App\Department;
use App\Departmentbed;
use App\DepartmentRevenue;
use Illuminate\Console\Command;

class FillFlddepartment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'departmentrevenue:department';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Replaces flddepartment field with department name';

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
        $departmentRevenues = DepartmentRevenue::all();
        foreach($departmentRevenues as $departmentRevenue){
            $flddepartment = null;
            if($departmentRevenue->location){
                $chkbed = Departmentbed::where('fldbed',$departmentRevenue->location)->first();
                if($chkbed){
                    $flddepartment = $chkbed->flddept;
                }else{
                    $chkdepart = Department::where('flddept',$departmentRevenue->location)->first();
                    if($chkdepart){
                        $flddepartment = $chkdepart->flddept;
                    }
                }
            }
            DepartmentRevenue::where('fldid',$departmentRevenue->fldid)->update([
                'flddepartment' => $flddepartment
            ]);
        }
    }
}
