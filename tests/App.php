<?php

declare(strict_types=1);

namespace hsdrg\tests;

use hsdrg\HSDrg;
use hsdrg\struct\MedicalRecord;
use hsdrg\Util;

error_reporting(E_ALL);

defined('DEBUG') || define('DEBUG', true);

class App
{
    public function run()
    {
        try {
            $this->echoMsg('读取drg数据', true);
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
            $this->echoMsg('创建DRG分组器', true);
            // 创建实例
            $driver = (new HSDrg)->store();
            // 加载drg计算数据
            $driver->load($code, $name, $data);
            $startTime = microtime(true);
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
                            $this->echoMsg("drg分组不一致，预期{$case['drg_code']}，实际{$drgCode}");
                        } else {
                            $success++;
                            $this->echoMsg("drg分组一致，预期{$case['drg_code']}，实际{$drgCode}");
                        }
                    } else {
                        // 比对失败，需要判定是否12、16，因为测试用例可能故意给错误的信息
                        if (\in_array(Util::getJState($jResult), [12, 16])) {
                            $data = (array)Util::getJData($jResult);
                            $drgCode = $data['code'];
                            if ($drgCode == $case['drg_code']) {
                                $success++;
                                $this->echoMsg("drg分组一致，预期{$case['drg_code']}，实际{$drgCode}");
                                continue;
                            }
                        } else if (isset($case['state']) && Util::getJState($jResult) == $case['state']) {
                            $success++;
                            $this->echoMsg("drg分组一致，预期错误码 {$case['state']}，实际错误码 " . Util::getJState($jResult));
                            continue;
                        }
                        $fail++;
                        $msg = Util::getJMsg($jResult);
                        $this->echoMsg("drg分组失败[{$msg}]，预期{$case['drg_code']}");
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
            $this->echoMsg("测试用例运行完毕，耗时 {$diffTime} {$diffTimeUnit}，共 {$num} 个用例，成功 {$success} 个，失败 {$fail} 个", true);
        } catch (\Throwable $ex) {
            var_dump($ex);
        }
    }

    private function echoMsg(string $msg, bool $force = false): void
    {
        if (!$force && !DEBUG) return;
        echo $msg, PHP_EOL;
    }
}

// 命令行入口文件
// 加载基础文件
require __DIR__ . '/../vendor/autoload.php';

// 应用初始化
(new App())->run();
