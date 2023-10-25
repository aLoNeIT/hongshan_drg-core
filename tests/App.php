<?php

declare(strict_types=1);

namespace hsdrg\tests;

use hsdrg\HSDrg;

class App
{
    public function run()
    {
        echo 'Hello World!', PHP_EOL;
        $driver = HSDrg::store();
        var_dump($driver);
    }
}

// 命令行入口文件
// 加载基础文件
require __DIR__ . '/../vendor/autoload.php';

// 应用初始化
(new App())->run();
