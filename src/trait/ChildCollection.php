<?php

declare(strict_types=1);

namespace hsdrg\trait;

use hsdrg\interfaces\IChildCollection;
use hsdrg\trait\ICDCollection;
use hsdrg\Util;

/**
 * 子项数据集合
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
trait ChildCollection
{
    /**
     * 子项的类类型
     *
     * @var string
     */
    protected $childClass = null;
    /**
     * 子对象集合，每个元素都是一个子对象实例
     *
     * @var array
     */
    protected $children = [];
    /**
     * 加载子数据
     *
     * @param array $data 子项数据，每个元素都是一个Collection对象
     * @param boolean $clear 是否清理已有的数据，默认true
     * @return static 返回当前对象
     */
    public function loadChildren(array $data, bool $clear = true): static
    {
        $clear && $this->children = [];
        if (!\is_null($this->childClass) && \class_exists($this->childClass)) {
            $childClass = $this->childClass;
            foreach ($data as $item) {
                /** @var \hsdrg\struct\Base $child */
                $child = new $childClass();
                $child->load($item);
                $this->children[] = $child;
                // 判断是否存在icd数据，如果存在，则加载icd数据
                if ($child instanceof ICDCollection && isset($item['icds'])) {
                    $child->loadICD($item['icds']);
                }
                // 再判断当前对象中是否存在children数据，如果存在，则加载子项数据
                if ($child instanceof ChildCollection && isset($item['children'])) {
                    $child->loadChildren($item['children']);
                }
            }
        }
        return $this;
    }
}
