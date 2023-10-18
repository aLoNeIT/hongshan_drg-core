<?php

declare(strict_types=1);

namespace hsdrg\struct;

use hsdrg\Util;

/**
 * 病历类
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class MedicalRecord
{
    /**
     * 就诊编码
     *
     * @var string|null
     */
    public $code = null;
    /**
     * 性别，1男，2女
     *
     * @var integer|null
     */
    public $sex = null;
    /**
     * 年龄，周岁
     *
     * @var integer|null
     */
    public $age = null;
    /**
     * 年龄，天数
     *
     * @var integer|null
     */
    public $ageDay = null;
    /**
     * 体重，单位g
     *
     * @var integer|null
     */
    public $weight = null;
    /**
     * 出生体重，单位g
     *
     * @var integer|null
     */
    public $birthWeight = null;
    /**
     * 科室编码
     *
     * @var string|null
     */
    public $inDepartment = null;
    /**
     * 住院天数
     *
     * @var integer|null
     */
    public $inDays = null;
    /**
     * 出院方式
     *
     * @var integer|null
     */
    public $outType = null;
    /**
     * 主要诊断编码
     *
     * @var string|null
     */
    public $principalDiagnosis = null;
    /**
     * 其他诊断编码数组
     *
     * @var array
     */
    public $secondaryDiagnosis = [];
    /**
     * 主要手术及操作编码
     *
     * @var string|null
     */
    public $majorProcedure = null;
    /**
     * 其他手术及操作编码数组
     *
     * @var array
     */
    public $secondaryProcedure = [];
}
