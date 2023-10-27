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
    /**
     * 配置信息
     *
     * @var array
     */
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
     * 当前创建的驱动
     *
     * @var array
     */
    protected $drivers = [];
    /**
     * 当前管理类实例
     *
     * @var static
     */
    private static $instance = null;
    /**
     * 构造函数
     * 
     * @param array $config 配置信息
     */
    public function __construct(array $config = [])
    {
        $this->config = \array_merge($this->config, $config);
    }
    /**
     * 获取当前管理类对象
     *
     * @param array $config 配置信息
     * @param boolean $newInstance 是否创建新的实例
     * 
     * @return static
     */
    public static function instance(array $config = [], bool $newInstance = false): static
    {
        if (\is_null(static::$instance) || $newInstance) {
            static::$instance = new static($config);
        }
        return static::$instance;
    }
    /**
     * 切换驱动
     *
     * @param string|null $driver 驱动名称
     * @param boolean $newInstance 是否创建新实例
     * 
     * @return Driver 返回创建的驱动对象
     */
    public function store(string $driver = null, bool $newInstance = false): Driver
    {
        $driver = $driver ?: $this->config['default'];
        // 驱动已经存在直接返回
        if (isset($this->drivers[$driver]) && !$newInstance) {
            return $this->drivers[$driver];
        }
        $config = $this->config['stores'][$driver];
        $type = $config['type'];
        // 实例化对象
        $class = "\\hsdrg\\driver\\{$type}";
        if (\class_exists($class)) {
            $driverObj = new $class($config);
        } elseif (\class_exists($type)) {
            $driverObj = new $type($config);
        } else {
            throw new HSDrgException("Driver {$type} not found");
        }
        $this->drivers[$driver] = $driverObj;
        return $this->drivers[$driver];
    }
}
