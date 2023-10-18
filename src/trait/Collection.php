<?php

declare(strict_types=1);

namespace hsdrg\trait;

use hsdrg\Util;

trait Collection
{
    /**
     * items中子项的类类型
     *
     * @var string
     */
    protected $itemClass = null;
    /**
     * 子对象集合，每个元素都是一个子对象实例
     *
     * @var array
     */
    protected $items = [];

    /**
     * 加载数据
     *
     * @param array $data 数据
     * @return static 返回当前对象
     */
    public function load(array $data): self
    {
        // 处理子项数据
        $children = null;
        if (isset($data['children'])) {
            $children = $data['children'];
            unset($data['children']);
        }
        // 获取当前对象的所有公共属性
        $props = Util::getPublicProps($this);
        // 遍历传递进来的数据，如果当前对象的属性存在，则赋值
        foreach ($data as $key => $value) {
            // data中的key是下划线命名法，而当前对象的属性是驼峰命名法，所以需要转换
            if (isset($props[Util::camel($key)])) {
                $this->$key = $value;
            }
        }
        // 加载子项数据
        if (!\is_null($children)) {
            $this->loadItems($children);
        }
        return $this;
    }
    /**
     * 加载子数据
     *
     * @param array $data 子项数据
     * @param boolean $clear 是否清理已有的数据，默认true
     * @return static 返回当前对象
     */
    public function loadItems(array $data, bool $clear = true): self
    {
        $clear && $this->items = [];
        if (!\is_null($this->itemClass) && \class_exists($this->itemClass)) {
            $itemClass = $this->itemClass;
            foreach ($data as $item) {
                /** @var Collection $itemObj */
                $itemObj = new $itemClass();
                $itemObj->load($item);
                $this->items[] = $itemObj;
            }
        }
        return $this;
    }
}
