<?php

declare(strict_types=1);

namespace hsdrg\struct;

use hsdrg\interfaces\IAdrgProcessor;
use hsdrg\interfaces\ICollection;
use hsdrg\interfaces\IDrgProcessor;
use hsdrg\processor\Base as BaseProcessor;
use hsdrg\processor\MultiDiagnosis as MDProcessor;
use hsdrg\processor\MultiProcedure as MPProcessor;
use hsdrg\processor\SignleDiagnosisAndMultiProcedure as SDAMPProcessor;
use hsdrg\processor\SignleDiagnosisAndProcedure as SDAPProcessor;
use hsdrg\processor\SingleDiagnosis as SDProcessor;
use hsdrg\processor\SingleProcedure as SPProcessor;
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
     * adrg规则集合，每个元素都是一个AdjacentDiagnosisRelatedGroupItem对象
     *
     * - 参考结构    
     * ```
     * [    
     *     'diagnosis' => [    
     *         0=>[AdjacentDiagnosisRelatedGroupItem,AdjacentDiagnosisRelatedGroupItem],    
     *     ],    
     *     'procedure' => [    
     *        1=>[AdjacentDiagnosisRelatedGroupItem,AdjacentDiagnosisRelatedGroupItem],    
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
     * 处理器
     *
     * @var object|null
     */
    private $processor = null;
    /**
     * 处理器类型映射
     */
    public const PROCESSOR_MAP = [
        1 => MDProcessor::class,
        2 => MPProcessor::class,
        3 => SDAMPProcessor::class,
        4 => SDAPProcessor::class,
        5 => SDProcessor::class,
        6 => SPProcessor::class
    ];

    /**
     * 获取adrg分组处理器
     *
     * @return AdrgProcessor 返回adrg处理器对象
     */
    public function getProcessor(): IAdrgProcessor
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
                $this->items[$typeName][$obj->index][] = $obj;
            }
        }
        return $this;
    }
    /** @inheritDoc */
    public function process(MedicalRecord $medicalRecord): array
    {
        $processor = $this->getProcessor();
        $result = $processor->detect($medicalRecord, $this->items);
        return $result ? Util::jsuccess($this->code) : Util::jerror();
    }
}
