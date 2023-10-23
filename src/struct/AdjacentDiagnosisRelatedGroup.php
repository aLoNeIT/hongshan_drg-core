<?php

declare(strict_types=1);

namespace hsdrg\struct;

use hsdrg\interfaces\ICollection;
use hsdrg\interfaces\IDetectProcessor;
use hsdrg\interfaces\IDrgProcessor;
use hsdrg\processor\adrg\{
    AnyProcedure,
    ExcludeProcedure,
    NoProcedure,
    SingleDiagnosis,
    SingleMajorProcedure,
    SinglePrincipalDiagnosis,
    SinglePrincipalDiagnosisAndMultiProcedure,
    SingleMajorProcedureAndSecondaryProcedure,
    SinglePrincipalDiagnosisAndTwoProcedure,
    SinglePrincipalDiagnosisOrMajorProcedure,
    TwoProcedure
};
use hsdrg\Util;

/**
 * 核心疾病诊断相关分组
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class AdjacentDiagnosisRelatedGroup extends Base implements IDrgProcessor, ICollection
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
    public $conditions = [];
    /**
     * 处理器类型
     *
     * @var integer|null
     */
    public $processorType = null;
    /**
     * adrg规则集合，kv结构，key为adrg编码，value为adrg对象
     *
     * - 参考结构    
     * ```
     * [    
     *     'diagnosis' => [    
     *         0=>['code'=>'AdjacentDiagnosisRelatedGroupItem','code'=>'AdjacentDiagnosisRelatedGroupItem'],    
     *     ],    
     *     'procedure' => [    
     *        1=>['code'=>'AdjacentDiagnosisRelatedGroupItem','code'=>'AdjacentDiagnosisRelatedGroupItem'],    
     *     ]    
     * ]    
     * ```
     * 
     * @var array
     */
    public $items = [
        'diagnosis' => [], //诊断
        'procedure' => [] // 手术或操作
    ];
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
    /**
     * 处理器类型映射
     * 
     * - 1：单主诊断
     * - 2：单主手术或操作
     * - 3：双手术
     * - 4：单主诊断+多手术组合
     * - 5：单诊断
     * - 6：任意手术
     * - 7：任意手术（排除指定手术）
     * - 8：无手术
     * - 9：主要诊断或主要手术操作
     * - 10：单主诊断和双手术
     */
    public const PROCESSOR_MAP = [
        1 => SinglePrincipalDiagnosis::class,
        2 => SinglePrincipalDiagnosisOrMajorProcedure::class,
        3 => TwoProcedure::class,
        4 => SinglePrincipalDiagnosisAndMultiProcedure::class,
        5 => SingleDiagnosis::class,
        6 => AnyProcedure::class,
        7 => ExcludeProcedure::class,
        8 => NoProcedure::class,
        9 => SinglePrincipalDiagnosisOrMajorProcedure::class,
        10 => SinglePrincipalDiagnosisAndTwoProcedure::class,
    ];

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
        $processMap = static::PROCESSOR_MAP;
        if (isset($processMap[$this->processorType])) {
            $this->processor = new $processMap[$this->processorType]();
        }
        return $this->processor;
    }
    /**
     * ADRG子项类型映射
     */
    public const ADRG_ITEM_TYPE_MAP = [
        1 => 'diagnosis',
        2 => 'procedure'
    ];
    /** @inheritDoc */
    public function loadItems(array $data): self
    {
        foreach ($data as $item) {
            $obj = new AdjacentDiagnosisRelatedGroupItem();
            $obj->load($item);
            $typeName = static::ADRG_ITEM_TYPE_MAP[$obj->type] ?? null;
            if (!\is_null($typeName)) {
                $this->items[$typeName][$obj->index][$obj->code] = $obj;
            }
        }
        return $this;
    }
    /** @inheritDoc */
    public function process(MedicalRecord $medicalRecord): array
    {
        // 计算当前conditions是否满足
        $result = Util::detectFormulaArray($medicalRecord, $this->conditions);
        if (false === $result) {
            return Util::jerror(11);
        }
        // 创建adrg处理器，进行检测
        $processor = $this->getProcessor();
        $result = $processor->detect($medicalRecord, $this->getCodes());
        return $result ? Util::jsuccess($this->code) : Util::jerror();
    }
    /**
     * 获取编码集合
     *
     * @return array 返回编码集合
     */
    protected function getCodes(): array
    {
        if (\is_null($this->codes)) {
            $codes = [];
            // 提取所有编码
            foreach (['diagnosis', 'procedure'] as $typeName) {
                foreach ($this->items[$typeName] as $idx => $items) {
                    $codes[$typeName][$idx] = \array_keys($items);
                }
            }
            $this->codes = $codes;
        }
        return $this->codes;
    }
}
