<?php

declare(strict_types=1);

namespace hsdrg\struct;

use hsdrg\trait\ItemStruct;

/**
 * 主要诊断大类的入组子项
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class MajorDiagnosticCategoryItem extends Base
{
    use ItemStruct;
    /**
     * 规则集合编码
     *
     * @var string|null
     */
    public $drgSetCode = null;
    /**
     * 疾病编码
     *
     * @var string|null
     */
    public $mdcCode = null;
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
}
