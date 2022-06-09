<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Permission related authorize message for individual Modules and submodules
    |--------------------------------------------------------------------------
    |
    | Basic Example to Get UnAuthorize Message
    | Usage : config('unauthorize-message.key')
    |
    |
    */

    'pharmacy_master' => [
        'medicine_grouping'  => [
            'view' => 'Unauthorize Access For Medicine Grouping View',
            'add' => 'Unauthorize Access For Medicine Grouping Create',
            'update' => 'Unauthorize Access For Medicine Grouping Update',
            'delete' => 'Unauthorize Access For Medicine Grouping Delete',
        ],
        'pharmacy-item-activation' => [
            'view' => 'Unauthorize Access For Pharmacy Item Activation View',
            'add' => 'Unauthorize Access For Pharmacy Item Activation Create',
            'update' => 'Unauthorize Access For Pharmacy Item Activation Update',
            'delete' => 'Unauthorize Access For Pharmacy Item Activation Delete',
        ],
        'out-of-order' => [
            'view' => 'Unauthorize Access For Out Of Order View',
            'add' => 'Unauthorize Access For Out Of Order Create',
            'update' => 'Unauthorize Access For Out Of Order Update',
            'delete' => 'Unauthorize Access For Out Of Order Delete',
        ],

    ],
    'item-master' => [
        'laboratory'  => [
            'view' => 'Unauthorize Access For Laboratory View',
            'add' => 'Unauthorize Access For Laboratory Create',
            'update' => 'Unauthorize Access For Laboratory Update',
            'delete' => 'Unauthorize Access For Laboratory Delete',
        ],
        'radiology'  => [
            'view' => 'Unauthorize Access For Radiology View',
            'add' => 'Unauthorize Access For Radiology Create',
            'update' => 'Unauthorize Access For Radiology Update',
            'delete' => 'Unauthorize Access For Radiology Delete',
        ],
        'procedures'  => [
            'view' => 'Unauthorize Access For Procedures View',
            'add' => 'Unauthorize Access For Procedures Create',
            'update' => 'Unauthorize Access For Procedures Update',
            'delete' => 'Unauthorize Access For Procedures Delete',
        ],
        'equipments'  => [
            'view' => 'Unauthorize Access For Equipments View',
            'add' => 'Unauthorize Access For Equipments Create',
            'update' => 'Unauthorize Access For Equipments Update',
            'delete' => 'Unauthorize Access For Equipments Delete',
        ],
        'gen-service'  => [
            'view' => 'Unauthorize Access For Gen Service View',
            'add' => 'Unauthorize Access For  Gen Service Create',
            'update' => 'Unauthorize Access  For Gen Service Update',
            'delete' => 'Unauthorize Access  For Gen Service Delete',
        ],
        'other-items'  => [
            'view' => 'Unauthorize Access For Other Items View',
            'add' => 'Unauthorize Access For Other Items Create',
            'update' => 'Unauthorize Access For Other Items Update',
            'delete' => 'Unauthorize Access For Other Items Delete',
        ],

    ]
];
