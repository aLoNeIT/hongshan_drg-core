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

    private static $instance = null;

    public static function instance(): self
    {
        if (\is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }
    /**
     * 切换驱动
     *
     * @param string|null $driver 驱动名称
     * @return Driver 返回创建的驱动对象
     */
    public static function store(string $driver = null): Driver
    {
        $instance = static::instance();
        $driver = $driver ?: $instance->config['default'];
        $config = $instance->config['stores'][$driver];
        $type = $config['type'];
        // 实例化对象
        $class = "\\hsdrg\\driver\\{$type}";
        if (\class_exists($class)) {
            return new $class($config);
        }
        // 尝试检测是否是自定义类
        if (!\class_exists($type)) {
            throw new HSDrgException("Driver {$type} not found");
        }
        return new $type($config);
    }
}
