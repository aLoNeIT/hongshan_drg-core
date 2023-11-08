<?php

declare(strict_types=1);

namespace hsdrg\struct;

use hsdrg\HSDrgConstant;
use hsdrg\interfaces\{IDetectProcessor, IDRGProcessor};
use hsdrg\trait\ICDCollection;
use hsdrg\Util;

/**
 * 核心疾病诊断相关分组
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class AdjacentDiagnosisRelatedGroup extends Base implements IDRGProcessor
{
    use ICDCollection;
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
     * 编码集合，用于快速查找，每个元素都是编码字符串
     *
     * - 参考结构
     * ```
     * $codes = [
     *     'diagnosis' => [], //诊断
     *     'procedure' => [] // 手术或操作
     * ]
     * ```
     * 
     * @var array|null
     */
    private $codes = null;
    /**
     * 处理器
     *
     * @var object|null
     */
    private $processor = null;
    /** @inherited */
    protected function initialize(): void
    {
        parent::initialize();
        $this->icdItemClass = AdjacentDiagnosisRelatedGroupItem::class;
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
        $processMap = HSDrgConstant::ADRG_PROCESSOR_MAP;
        if (isset($processMap[$this->processorType])) {
            $this->processor = new $processMap[$this->processorType]();
        }
        return $this->processor;
    }
    /** @inheritDoc */
    public function process(MedicalRecord $medicalRecord): array
    {
        // 计算当前condition是否满足
        $result = Util::detectFormulaArray($medicalRecord, $this->condition);
        if (false === $result) {
            return Util::jerror(15);
        }
        // 创建adrg处理器，进行检测
        $processor = $this->getProcessor();
        $result = $processor->detect($medicalRecord, $this->icdCodes);
        // if ('Y1' == $this->code) {
        //     var_dump([$this->icdCodes, $processor,  $medicalRecord->toArray(), $result]);
        // }
        return $result ? Util::jsuccess($this->code, [
            'code' => $this->code,
            'name' => $this->name
        ]) : Util::jerror();
    }
}
