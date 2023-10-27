<?php

declare(strict_types=1);

namespace hsdrg\struct;

/**
 * 核心疾病诊断相关分组子项
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class AdjacentDiagnosisRelatedGroupItem extends Base
{
    /**
     * 规则集合编码
     *
     * @var string
     */
    public $drgSetCode = null;
    /**
     * mdc编码
     *
     * @var string|null
     */
    public $mdcCode = null;
    /**
     * adrg分组编码
     *
     * @var string|null
     */
    public $adrgCode = null;
    /**
     * 编码
     *
     * @var string|null
     */
    public $code = null;
    /**
     * 名称
     * 
     * @var string|null
     */
    public $name = null;
    /**
     * 类型，1诊断，2手术
     *
     * @var integer|null
     */
    public $type = null;
    /**
     * 内部分组序号
     *
     * @var integer
     */
    public $index = 0;
}
