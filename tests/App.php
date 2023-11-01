<?php

declare(strict_types=1);

namespace hsdrg\tests;

use hsdrg\HSDrg;
use hsdrg\struct\MedicalRecord;
use hsdrg\Util;

error_reporting(E_ALL);

class App
{
    public function run()
    {
        echo '读取drg数据', PHP_EOL;
        // 读取drg数据
        $file = dirname(__DIR__) . '/data.json';
        $content = file_get_contents($file);
        $json = [
            'code' => '1',
            'name' => 'CHS-DRG 1.1 版 （医保编码 2.0)',
            'data' => \json_decode($content, true)
        ];
        [
            'code' => $code,
            'name' => $name,
            'data' => $data
        ] = $json;
        echo '创建DRG分组器', PHP_EOL;
        // 创建实例
        $driver = (new HSDrg)->store();
        // 加载drg计算数据
        $driver->load($code, $name, $data);
        // 获取测试用例数据
        $dir = __DIR__ . '/';
        $cases = include $dir . 'Cases.php';
        foreach ($cases as $idx => $case) {
            echo "读取第[{$idx}]个测试用例", PHP_EOL;
            echo "用例数据:", PHP_EOL;
            echo \json_encode($case['medical_record'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), PHP_EOL;
            $medicalRecord = (new MedicalRecord())->load($case['medical_record']);
            $jResult = $driver->process($code, $medicalRecord);
            if (Util::isSuccess($jResult)) {
                // 获取成功，则对比下是否和预定的drg分组一致
                $drgCode = Util::getJMsg($jResult);
                if ($drgCode != $case['drg_code']) {
                    echo "drg分组不一致，预期{$case['drg_code']}，实际{$drgCode}", PHP_EOL;
                } else {
                    echo "drg分组一致，预期{$case['drg_code']}，实际{$drgCode}", PHP_EOL;
                }
            }
        }
    }
}

// 命令行入口文件
// 加载基础文件
require __DIR__ . '/../vendor/autoload.php';

// 应用初始化
(new App())->run();
