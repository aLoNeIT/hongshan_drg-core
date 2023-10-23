<?php

declare(strict_types=1);

namespace hsdrg;

/**
 * 红杉健康drg核心类
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class HSDrg
{
    protected $config = [
        // 驱动方式
        'default' => 'chs',
        'stores' => [
            'chs' => [
                'type' => 'Chs',
            ],
        ],
    ];
    /**
     * 切换驱动
     *
     * @param string|null $driver 驱动名称
     * @return Driver 返回创建的驱动对象
     */
    public function store(string $driver = null): Driver
    {
        $driver = $driver ?: $this->config['default'];
        $config = $this->config['stores'][$driver]['type'];
        return new $driver($config);
    }
}
