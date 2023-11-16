<?php

declare(strict_types=1);

namespace hsdrg\struct;

use hsdrg\HSDrgConstant;
use hsdrg\interfaces\IDetectProcessor;
use hsdrg\interfaces\{IChildCollection, IDRGProcessor};
use hsdrg\trait\{ChildCollection, ICDCollection};
use hsdrg\Util;

/**
 * 主要诊断大类
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class MajorDiagnosticCategory extends Base implements IDRGProcessor, IChildCollection
{
    use ChildCollection;
    use ICDCollection;

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
    public $condition = [];
    /**
     * 处理器类型
     *
     * @var integer|null
     */
    public $processorType = null;
    /**
     * 处理器
     *
     * @var object|null
     */
    private $processor = null;

    /** @inheritDoc */
    protected function initialize(): void
    {
        parent::initialize();
        $this->childClass = AdjacentDiagnosisRelatedGroup::class;
        $this->icdItemClass = MajorDiagnosticCategoryItem::class;
    }
    /**
     * 获取adrg分组处理器
     *
     * @return IDetectProcessor 返回adrg处理器对象
     */
    public function getProcessor(): IDetectProcessor
    {
        if (!\is_null($this->processor)) {
            return $this->processor;
        }
        $processMap = HSDrgConstant::MDC_PROCESSOR_MAP;
        if (isset($processMap[$this->processorType])) {
            $this->processor = new $processMap[$this->processorType]();
        }
        return $this->processor;
    }
    /** @inheritDoc */
    public function process(MedicalRecord $medicalRecord): array
    {
        // 先根据当前mdc的入组规则进行匹配
        // 要求先满足condition内的所有条件，再满足病案信息中的主要诊断符合diagosis内的任意一个
        $result = Util::detectFormulaArray($medicalRecord, $this->condition);
        if (false === $result) {
            return Util::jerror(11);
        }
        // 获取对应处理器，使用处理器进行匹配
        $processor = $this->getProcessor();
        $result = $processor->detect($medicalRecord, $this->icdCodes);
        if (!$result) {
            return Util::jerror(11);
        }
        // 满足条件，开始进行adrg的匹配
        $adrgCode = null;
        $adrgData = [];
        /** @var AdjacentDiagnosisRelatedGroup $adrg */
        foreach ($this->children as $adrg) {
            $jResult = $adrg->process($medicalRecord);
            if (Util::isSuccess($jResult)) {
                $adrgCode = Util::getJMsg($jResult);
                $adrgData = Util::getJData($jResult);
                break;
            }
        }
        // 如果adrgCode为null，说明没有匹配到adrg，此时返回特定错误码，和歧义组编码
        if (\is_null($adrgCode)) {
            return \in_array($this->code, ['A'])
                ? Util::jerror(11)
                : Util::jerror(12, "{$this->code}QY");
        }
        return Util::jsuccess("{$this->code}{$adrgCode}", [
            'code' => $this->code,
            'name' => $this->name,
            'adrg' => $adrgData
        ]);
    }
}
