<?php

declare(strict_types=1);

namespace hsdrg\struct;

use hsdrg\trait\Collection;

/**
 * 严重并发症或合并症
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class MajorComplicationComorbidity extends Base
{
    use Collection;
    /**
     * 规则集合编码
     *
     * @var string|null
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
     * 排除表编码
     *
     * @var string|null
     */
    public $excludeGroupCode = null;
}
