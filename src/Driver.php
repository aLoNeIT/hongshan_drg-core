<?php

declare(strict_types=1);

namespace hsdrg;

/**
 * drg分组驱动基类
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class Driver
{
    /**
     * 配置信息
     *
     * @var array
     */
    protected $config = [];
    /**
     * 错误码
     *
     * @var array
     */
    protected $errCode = [
        '10' => '未匹配到符合要求的DRG分组',
        '11' => '不符合当前MDC入组规则',
        '12' => '未匹配到ADRG分组，进入歧义组',
        '13' => '次要诊断不存在于MCC或CC数据中',
        '14' => '主要诊断未通过MCC或CC排除表校验',
    ];

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->initialize();
    }
    /**
     * 初始化函数
     *
     * @return void
     */
    protected function initialize(): void
    {
    }
}
