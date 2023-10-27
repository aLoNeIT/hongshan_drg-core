<?php

declare(strict_types=1);

namespace hsdrg\struct;

/**
 * 医疗保险drg付费分组
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class MedicareDiagnosisRelatedGroup extends Base
{
    /**
     * 规则集合编码
     *
     * @var string
     */
    public $drgSetCode = null;
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
     * 权重
     *
     * @var integer|null
     */
    public $weight = null;
}
