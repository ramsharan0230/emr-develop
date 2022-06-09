<?php

use Illuminate\Database\Seeder;

class RadioTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $radiotemplate = [
            ['fldtestid' => 'X-RAY FINGER', 'flddescription' => 'X-RAY FINGER description'],
            ['fldtestid' => 'X-RAY HAND', 'flddescription' => 'X-RAY HAND description'],
            ['fldtestid' => 'X-RAY WRIST JOINT', 'flddescription' => 'X-RAY WRIST JOINT description'],
            ['fldtestid' => 'X-RAY ELBOW JOINT', 'flddescription' => 'X-RAY ELBOW JOINT description'],
            ['fldtestid' => 'X-RAY HUMERUS', 'flddescription' => 'X-RAY HUMERUS description'],
            ['fldtestid' => 'X-RAY SHOULDER JOINT', 'flddescription' => 'X-RAY SHOULDER JOINT description'],
            ['fldtestid' => 'X-RAY CLAVICLE', 'flddescription' => 'X-RAY CLAVICLE description'],
            ['fldtestid' => 'X-RAY STERNOCLAVICULER JOINT', 'flddescription' => 'X-RAY STERNOCLAVICULER JOINT description'],
            ['fldtestid' => 'X-RAY ACROMIOCLAVICULAR JOINT', 'flddescription' => 'X-RAY ACROMIOCLAVICULAR JOINT description'],
            ['fldtestid' => 'X-RAY CHEST', 'flddescription' => 'X-RAY CHEST description'],
            ['fldtestid' => 'X-RAY STERNUM', 'flddescription' => 'X-RAY STERNUM description'],
            ['fldtestid' => 'X-RAY CERVICAL SPINE', 'flddescription' => 'X-RAY CERVICAL SPINE description'],
            ['fldtestid' => 'X-RAY THORACIC SPINE', 'flddescription' => 'X-RAY THORACIC SPINE description'],
            ['fldtestid' => 'X-RAY LUMBAR SPINE', 'flddescription' => 'X-RAY LUMBAR SPINE description'],
            ['fldtestid' => 'X-RAY DORSOLUMBAR SPINE/ THORACOLUMABAR SPINE', 'flddescription' => 'X-RAY DORSOLUMBAR SPINE/ THORACOLUMABAR SPINE description'],
            ['fldtestid' => 'X-RAY PELVIS & HIP-JOINT', 'flddescription' => 'X-RAY PELVIS & HIP-JOINT description'],
            ['fldtestid' => 'X-RAY SACRO-ILLIAC JOINT', 'flddescription' => 'X-RAY SACRO-ILLIAC JOINT description'],
            ['fldtestid' => 'X-RAY THIGH', 'flddescription' => 'X-RAY THIGH description'],
            ['fldtestid' => 'X-RAY KNEE JOINT', 'flddescription' => 'X-RAY KNEE JOINT description'],
            ['fldtestid' => 'X-RAY LOWER LEG', 'flddescription' => 'X-RAY LOWER LEG description'],
            ['fldtestid' => 'X-RAY ANKLE', 'flddescription' => 'X-RAY ANKLE description'],
            ['fldtestid' => 'X-RAY CALCANEUM/HEEL', 'flddescription' => 'X-RAY CALCANEUM/HEEL description'],
            ['fldtestid' => 'X-RAY FOOT', 'flddescription' => 'X-RAY FOOT description'],
            ['fldtestid' => 'X-RAY TOE', 'flddescription' => 'X-RAY TOE description'],
            ['fldtestid' => 'X-RAY ABDOMEN NORMAL', 'flddescription' => 'X-RAY ABDOMEN NORMAL description'],
        ];
        \App\RadioTemplate::insert($radiotemplate);
    }
}
