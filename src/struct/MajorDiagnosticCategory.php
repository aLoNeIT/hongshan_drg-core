<?php

declare(strict_types=1);

namespace hsdrg\struct;

use hsdrg\interfaces\ICollection;
use hsdrg\interfaces\IDRGProcessor;
use hsdrg\trait\Collection;
use hsdrg\Util;

/**
 * 主要诊断大类
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class MajorDiagnosticCategory extends Base implements IDRGProcessor, ICollection
{
    use Collection;

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
     * 主要诊断集合，每个元素都是一个icd9|icd10编码
     * 
     * @var array
     */
    public $diagnosis = [];
    /**
     * 入组规则集合，自定义的条件表达式，会在入组前先根据规则做校验
     * 
     * [
     *     ['age','<', 29 ], // 年龄小于29天
     *     ['birth_weight','<',1500], // 出生体重小于1500g
     *     ['sex','=',1]
     * ]
     *
     * @var array
     */
    public $conditions = [];

    /** @inheritDoc */
    protected function initialize(): void
    {
        parent::initialize();
        $this->itemClass = AdjacentDiagnosisRelatedGroup::class;
    }
    /** @inheritDoc */
    public function process(MedicalRecord $medicalRecord): array
    {
        // 先根据当前mdc的入组规则进行匹配
        // 要求先满足condition内的所有条件，再满足病案信息中的主要诊断符合diagosis内的任意一个
        $result = Util::detectFormulaArray($medicalRecord, $this->conditions);
        if (false === $result) {
            return Util::jerror(11);
        }
        // 特殊的MDCZ，需要做多诊断匹配
        if ('Z' == Util::upper($this->code)) {
        } else {
            // 要求当前病案信息的主要诊断属于当前mdc的诊断集合
            if (!isset($this->diagnosis[$medicalRecord->principalDiagnosis])) {
                return Util::jerror(11);
            }
        }
        // 满足条件，开始进行adrg的匹配
        $adrgCode = null;
        /** @var AdjacentDiagnosisRelatedGroup $ardg */
        foreach ($this->items as $ardg) {
            $jResult = $ardg->process($medicalRecord);
            if (Util::isSuccess($jResult)) {
                $adrgCode = Util::getJMsg($jResult);
                break;
            }
        }
        // 如果adrgCode为null，说明没有匹配到adrg，此时返回特定错误码，和歧义组编码
        if (\is_null($adrgCode)) {
            return Util::jerror(12, "{$this->code}QY");
        }
        return Util::jsuccess("{$this->code}{$adrgCode}");
    }
}
