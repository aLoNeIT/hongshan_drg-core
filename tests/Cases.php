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
            'principal_diagnosis' => 's01.800x011',
            'secondary_diagnosis' => [],
            'major_procedure' => null,
            'secondary_procedure' => []
        ]
    ]
];
