<?php

declare(strict_types=1);
/**
 * 本文件是所有的测试数据
 */
return [
    [
        // 单诊断带严重并发症
        'drg_code' => 'GE19',
        'medical_record' => [
            'code' => '1',
            'sex' => 2,
            'age' => 30,
            'in_days' => 15,
            'out_type' => 1,
            'principal_diagnosis' => 'C18.100',
            'secondary_diagnosis' => [],
            'major_procedure' => '47.0901',
            'secondary_procedure' => []
        ]
    ],
    [
        // 单诊断带严重并发症
        'drg_code' => 'JB19',
        'medical_record' => [
            'code' => '0100000808',
            'sex' => 1,
            'age' => 32,
            'in_days' => 2,
            'out_type' => 1,
            'principal_diagnosis' => 'C44.501',
            'secondary_diagnosis' => [],
            'major_procedure' => '85.5300x001',
            'secondary_procedure' => []
        ]
    ],
    [
        // 单诊断带严重并发症
        'drg_code' => 'IB19',
        'medical_record' => [
            'code' => '0100000809',
            'sex' => 1,
            'age' => 32,
            'in_days' => 2,
            'out_type' => 1,
            'principal_diagnosis' => 'A18.000x047+M49.0*',
            'secondary_diagnosis' => [],
            'major_procedure' => '03.0900x025',
            'secondary_procedure' => []
        ]
    ],
    [
        // 单诊断带严重并发症
        'drg_code' => 'GE19',
        'medical_record' => [
            'code' => '0100000509',
            'sex' => 1,
            'age' => 99,
            'in_days' => 2,
            'principal_diagnosis' => 'C18.100',
            'secondary_diagnosis' => [],
            'major_procedure' => '47.0100',
            'secondary_procedure' => []
        ]
    ],
    [
        // 单诊断带严重并发症  期望入组  
        'drg_code' => 'NA19',
        'medical_record' => [
            'code' => '0100000509',
            'sex' => 2,
            'age' => 99,
            'in_days' => 2,
            'out_type' => '',
            'principal_diagnosis' => 'C46.700x001',
            'secondary_diagnosis' => ['C51.000'],
            'major_procedure' => '68.6100x001',
            'secondary_procedure' => []
        ]
    ],
    [
        // 单诊断带严重并发症  期望入组  
        'drg_code' => 'WB19',
        'medical_record' => [
            'code' => '0100000509',
            'sex' => 2,
            'age' => 99,
            'in_days' => 2,
            'out_type' => '',
            'principal_diagnosis' => 'T35.700x009',
            'secondary_diagnosis' => ['T31.200'],
            'major_procedure' => '04.0408',
            'secondary_procedure' => []
        ]
    ],
    [
        // 单诊断带严重并发症  期望入组  
        'drg_code' => 'JB39',
        'medical_record' => [
            'code' => '0100000643',
            'sex' => 2,
            'age' => 67,
            'in_days' => 2,
            'out_type' => '',
            'principal_diagnosis' => 'C50.000',
            'secondary_diagnosis' => ['C44.501'],
            'major_procedure' => '85.2100x003',
            'secondary_procedure' => []
        ]
    ],
    [
        // 单诊断带严重并发症  期望入组  
        'drg_code' => 'JB19',
        'medical_record' => [
            'code' => '0100000644',
            'sex' => 2,
            'age' => 67,
            'in_days' => 2,
            'out_type' => '',
            'principal_diagnosis' => 'C44.501',
            'secondary_diagnosis' => [],
            'major_procedure' => '85.5300x001',
            'secondary_procedure' => []
        ]
    ],
    [
        // 单诊断带严重并发症  期望入组  
        'drg_code' => 'FK29',
        'medical_record' => [
            'code' => '0310039724',
            'sex' => 2,
            'age' => 69,
            'in_days' => 2,
            'out_type' => 1,
            'principal_diagnosis' => 'I11.001',
            'secondary_diagnosis' => ['E11.900', 'I25.103', 'Z95.501', 'I50.900x007', 'I10.x04',],
            'major_procedure' => '00.5000x001',
            'secondary_procedure' => []
        ]
    ],
    [
        // 单诊断带严重并发症  期望入组  
        'drg_code' => 'IU15',
        'medical_record' => [
            'code' => '13660003863',
            'sex' => 2,
            'age' => 61,
            'in_days' => 2,
            'out_type' => 1,
            'principal_diagnosis' => 'M17.000',
            'secondary_diagnosis' => ['B02.202+G53.0*', 'B02.202+G53.0*', 'B02.202+G53.0*'],
            'major_procedure' => null,
            'secondary_procedure' => []
        ]
    ],
    [
        // 单诊断带严重并发症  期望入组--BB29 ; 实际入组--BE19
        'drg_code' => 'BB25',
        'medical_record' => [
            'code' => '0100000509',
            'sex' => 2,
            'age' => 99,
            'in_days' => 2,
            'out_type' => '',
            'principal_diagnosis' => 'I60.000',
            'secondary_diagnosis' => [],
            'major_procedure' => '39.5900x013',
            'secondary_procedure' => []
        ]
    ],
    [
        // 单诊断带严重并发症  期望入组--BB29 ; 实际入组--BC19
        'drg_code' => 'BB25',
        'medical_record' => [
            'code' => '0100000509',
            'sex' => 2,
            'age' => 99,
            'in_days' => 2,
            'out_type' => '',
            'principal_diagnosis' => 'I60.000',
            'secondary_diagnosis' => [],
            'major_procedure' => '39.5101',
            'secondary_procedure' => []
        ]
    ],
    [
        'drg_code' => 'BB25',
        'medical_record' => [
            'code' => '0100000509',
            'sex' => 2,
            'age' => 99,
            'in_days' => 2,
            'out_type' => '',
            'principal_diagnosis' => 'I60.000',
            'secondary_diagnosis' => [],
            'major_procedure' => '01.2401',
            'secondary_procedure' => []
        ]
    ],
    [
        'drg_code' => 'BB25',
        'medical_record' => [
            'code' => '0100000509',
            'sex' => 1,
            'age' => 38,
            'in_days' => 2,
            'out_type' => '',
            'principal_diagnosis' => 'I60.000',
            'secondary_diagnosis' => [],
            'major_procedure' => '39.5900x013',
            'secondary_procedure' => []
        ]
    ],
    [
        'drg_code' => 'BB25',
        'medical_record' => [
            'code' => '0100000863',
            'sex' => 1,
            'age' => 0,
            'in_days' => 2,
            'out_type' => '',
            "birth_weight" => '',
            "weight" => '',
            'principal_diagnosis' => 'I60.000',
            'secondary_diagnosis' => [],
            'major_procedure' => '39.5900x013',
            'secondary_procedure' => []
        ]
    ],
    [
        // 单诊断带严重并发症  期望入组--ZZ15 ; 实际入组--LR15
        'drg_code' => 'ZZ11',
        'medical_record' => [
            'code' => '0100000509',
            'sex' => 1,
            'age' => 99,
            'in_days' => 2,
            'out_type' => '',
            'principal_diagnosis' => 'T79.500x002',
            'secondary_diagnosis' => ['S14.300'],
            'major_procedure' => '',
            'secondary_procedure' => []
        ]
    ]
];
