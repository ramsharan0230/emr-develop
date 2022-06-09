<?php

use Illuminate\Database\Seeder;

class RequestMacAccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        $macAccessData = \App\MacAccess::all();

        foreach ($macAccessData as $mac){
            if (!\App\RequestMacAccess::where('flduserid', $mac->fldhostuser)->where('hostmac', $mac->fldhostmac)->exists()){
                $requestData['hostmac'] = $mac->fldhostmac;
                $requestData['flduserid'] = $mac->fldhostuser;
                $requestData['password'] = $mac->fldhostpass;
                $requestData['category'] = $mac->fldcomp;
                $requestData['status'] = strtolower($mac->fldaccess);
                \App\RequestMacAccess::insert($requestData);
            }
        }
        Schema::enableForeignKeyConstraints();
    }
}
