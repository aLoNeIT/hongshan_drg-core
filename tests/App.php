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
        $startTime = microtime(true);
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
        $success = 0;
        $fail = 0;
        $num = 0;
        for ($i = 0; $i < 1; $i++) {
            foreach ($cases as $idx => $case) {
                $num++;
                // echo "读取第[{$num}]个测试用例", PHP_EOL;
                // echo "用例数据:", PHP_EOL;
                // echo \json_encode($case['medical_record'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), PHP_EOL;
                $medicalRecord = (new MedicalRecord())->load($case['medical_record']);
                $jResult = $driver->process($code, $medicalRecord);
                if (Util::isSuccess($jResult)) {
                    // 获取成功，则对比下是否和预定的drg分组一致
                    $drgCode = Util::getJMsg($jResult);
                    if ($drgCode != $case['drg_code']) {
                        $fail++;
                        // echo "drg分组不一致，预期{$case['drg_code']}，实际{$drgCode}", PHP_EOL;
                    } else {
                        $success++;
                        var_dump($jResult);
                        // echo "drg分组一致，预期{$case['drg_code']}，实际{$drgCode}", PHP_EOL;
                    }
                } else {
                    $fail++;
                    $msg = Util::getJMsg($jResult);
                    // echo "drg分组失败[{$msg}]，预期{$case['drg_code']}", PHP_EOL;
                }
            }
        }

        $endTime = microtime(true);
        $diffTime = $endTime - $startTime;
        $diffTimeUnit = '毫秒';
        if ($diffTime > 1) {
            $diffTime = round($endTime - $startTime, 3);
            $diffTimeUnit = '秒';
        } else {
            $diffTime = round(($endTime - $startTime) * 1000, 3);
        }
        echo "测试用例运行完毕，耗时 {$diffTime} {$diffTimeUnit}，共 {$num} 个用例，成功 {$success} 个，失败 {$fail} 个", PHP_EOL;
    }
}

// 命令行入口文件
// 加载基础文件
require __DIR__ . '/../vendor/autoload.php';

// 应用初始化
(new App())->run();
