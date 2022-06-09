<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Custom User Defined Constants
    |--------------------------------------------------------------------------
    |
    | Usage : config('filename.key')
    |
    |
    */
    'default_time_zone' => 'Asia/Kathmandu',
    'current_date' => \Carbon\Carbon::parse()->timezone('Asia/Kathmandu')->format('Y-m-d'),
    'current_date_time' => \Carbon\Carbon::parse()->timezone('Asia/Kathmandu')->format('Y-m-d H:i:s'),
    'firebase_notification_server_api_key' =>'AAAACx-0esU:APA91bEcVLqFZiQDh-exiUMH-m-n8P2BPtUVYu2MozCgkJ9Lty7NZ6x-3jyRecm-5kyohYP-pqrIbzEbpbnKSiREwonohfGLNKkmnR9zvjh6DpyzTP3oJaNRhR8OnDiy8-anyflK1G9P',

    'role_super_admin' => 1,
    'role_default_user' => 2,


    'max_total_records' => 20,
    'max_active_records' => 4,
    'hospital_name' => 'Cogent Queue Management',
    'first_menu' => 'Consultants',
    'second_menu' => 'Pharmacy',
    'third_menu' => 'Laboratory',
    'forth_menu' => 'Radiology',

    'gcs_eye_response' => [
        'Spontaneous' => 4,
        'To speech' => 3,
        'To pain' => 2,
        'None' => 1
    ],
    'gcs_verbal_response' => [
        'Oriented' => 5,
        'Confused' => 4,
        'Words' => 3,
        'Sounds' => 2,
        'None' => 1
    ],
    'gcs_motor_response' => [
        'Obeys Command' => 6,
        'localizing' => 5,
        'Normal Flexion' => 4,
        'Abnormal Flexion' => 3,
        'Extension' => 2,
        'None' => 1
    ],
    'pupils_reaction' => [
        'Normal Reaction' => '+',
        'No Reaction' => '-',
        'Sluggish Reaction' => '+-',
    ],

    'air_entry' => [
        'Bilaterally_equal' => 'BE',
        'Decreased_on_the_left_side' => '↓L',
        'Decreased_on_the_right' => '↓R',
    ],

    'wheeze' => [
        'No_wheeze' => 'x',
        'Bilateral_wheeze' => 'WB',
        'Wheeze_on_the_left_side' => 'WL',
        'Wheeze_on_the_right_side' => 'WR',
    ],

    'crackles' => [
        'No_cracked' => 'x',
        'Bilateral_crackles' => 'CB',
        'Crackles_on_the_left_side' => 'CL',
        'Crackles_on_the_right_side' => 'CR',
    ],

    'position' => [
        'Supine' => 'S',
        'Left_latera' => 'L',
        'Right_lateral' => 'R',
        'Prone' => 'P',
    ],

    'vitals' => [
        'Pulse Rate' => '*', //red dot
        'Systolic BP' => html_entity_decode('&#9660;'),
        'Diastolic BP' => html_entity_decode('&#9650;'),
        'Respiratory Rate' => 'x', //black cross
        'O2 Saturation' => 'x', //blue cross
        'Temperature (F)' => '*',// black dot
    ],

    'vaps' => [
        'YES' => html_entity_decode('&#10003;'),
        'NO' => 'x',
    ],

    'space' => [
        'blank' => html_entity_decode('&nbsp;'),
    ],

    'ambulation' => [
        'wheel_chair' => 'W',
        'support' => 'S',
//        'NO' => 'x',
    ],
];
