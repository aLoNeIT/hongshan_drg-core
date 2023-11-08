<?php

declare(strict_types=1);
/**
 * 本文件是所有的测试数据
 */
return [
    [
        // 单诊断（无并发症）
        'drg_code' => 'BR15',
        'medical_record' => [
            'code' => '1',
            'sex' => 2,
            'age' => 30,
            'in_days' => 15,
            'out_type' => 1,
            'principal_diagnosis' => 'G91.000x002',
            'secondary_diagnosis' => [],
            'major_procedure' => null,
            'secondary_procedure' => []
        ]
    ], [
        // 单诊断带严重并发症
        'drg_code' => 'BR11',
        'medical_record' => [
            'code' => '1',
            'sex' => 2,
            'age' => 30,
            'in_days' => 15,
            'out_type' => 1,
            'principal_diagnosis' => 'G91.000x002',
            'secondary_diagnosis' => ['A01.100'],
            'major_procedure' => null,
            'secondary_procedure' => []
        ]
    ], [
        // 单主诊断+单主手术（无并发症）
        'drg_code' => 'BB15',
        'medical_record' => [
            'code' => '1',
            'sex' => 2,
            'age' => 30,
            'in_days' => 15,
            'out_type' => 1,
            'principal_diagnosis' => 'S01.800x011',
            'secondary_diagnosis' => [],
            'major_procedure' => '01.0900x006',
            'secondary_procedure' => []
        ]
    ], [
        // 单主诊断+单主手术（严重并发症）
        'drg_code' => 'BB11',
        'medical_record' => [
            'code' => '1',
            'sex' => 2,
            'age' => 30,
            'in_days' => 15,
            'out_type' => 1,
            'principal_diagnosis' => 'S01.800x011',
            'secondary_diagnosis' => ['A01.100'],
            'major_procedure' => '01.0900x006',
            'secondary_procedure' => []
        ]
    ], [
        // 单手术
        'drg_code' => 'BB25',
        'medical_record' => [
            'code' => '1',
            'sex' => 2,
            'age' => 30,
            'in_days' => 15,
            'out_type' => 1,
            'principal_diagnosis' => 'A39.900',
            'secondary_diagnosis' => [],
            'major_procedure' => '01.0900x006',
            'secondary_procedure' => []
        ]
        // ], [
        //     // 歧义组
        //     'drg_code' => 'BQY',
        //     'medical_record' => [
        //         'code' => '1',
        //         'sex' => 2,
        //         'age' => 30,
        //         'age_day' => null,
        //         'weight' => null,
        //         'birth_weight' => null,
        //         'in_department' => null,
        //         'in_days' => 15,
        //         'out_type' => 1,
        //         'principal_diagnosis' => 'S01.800x011',
        //         'secondary_diagnosis' => ['A01.100'],
        //         'major_procedure' => '81.4900x005',
        //         'secondary_procedure' => []
        //     ]
    ], [
        // 0000组实例一  主诊断代码无效
        'drg_code' => '0000',
        'medical_record' => [
            'code' => '1',
            'sex' => 2,
            'age' => 30,
            'age_day' => null,
            'weight' => null,
            'birth_weight' => null,
            'in_department' => null,
            'in_days' => 15,
            'out_type' => 1,
            'principal_diagnosis' => '123',
            'secondary_diagnosis' => ['A01.100'],
            'major_procedure' => '81.4900x005',
            'secondary_procedure' => []
        ]
    ], [
        // 0000组实例二  性别不符合诊断大类
        'drg_code' => '0000',
        'medical_record' => [
            'code' => '1',
            'sex' => 2,
            'age' => 30,
            'age_day' => null,
            'weight' => null,
            'birth_weight' => null,
            'in_department' => null,
            'in_days' => 15,
            'out_type' => 1,
            'principal_diagnosis' => 'A18.100x018+N51.8*',
            'secondary_diagnosis' => ['A01.100'],
            'major_procedure' => '81.4900x005',
            'secondary_procedure' => []
        ]
    ], [
        // 00组实例三  年龄不符合诊断大类
        'drg_code' => '0000',
        'medical_record' => [
            'code' => '1',
            'sex' => 2,
            'age' => 30,
            'age_day' => null,
            'weight' => null,
            'birth_weight' => null,
            'in_department' => null,
            'in_days' => 15,
            'out_type' => 1,
            'principal_diagnosis' => 'A04.000x001',
            'secondary_diagnosis' => ['A01.100'],
            'major_procedure' => '81.4900x005',
            'secondary_procedure' => []
        ]
    ]
];