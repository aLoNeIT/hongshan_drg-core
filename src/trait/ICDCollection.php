<?php

declare(strict_types=1);

namespace hsdrg\trait;

use hsdrg\HSDrgException;
use hsdrg\struct\InternationalClassificationDiseases;

/**
 * ICD数据集合
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
trait ICDCollection
{
    /**
     * icd数据集合对应的类名
     *
     * @var string|null
     */
    protected $icdItemClass = null;
    /**
     * icd数据集合
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
    protected $icdCollection = [
        'diagnosis' => [],
        'procedure' => []
    ];
    /**
     * icd数据集合，纯编码，用于分组处理
     *
     * - 参考结构    
     * ```
     * [
     *     'diagnosis' => [
     *         0=>['code1','code2'],
     *     ],
     *     'procedure' => [
     *        1=>['code1','code2'],
     *     ]
     * ]
     * ```
     * 
     * @var array
     */
    protected $icdCodes = [
        'diagnosis' => [],
        'procedure' => []
    ];
    /**
     * 加载数据
     *
     * @param array $data 数据
     * @return static 返回当前对象
     */
    public function loadICD(array $data): static
    {
        $class = $this->icdItemClass;
        if (!class_exists($class)) {
            throw new HSDrgException('ICD子项类不存在');
        }
        // 循环每一条数据，转化为需要的对象
        foreach ($data as $item) {
            /** @var \hsdrg\struct\Base $obj */
            $obj = new $class();
            $obj->load($item);
            /** @var ItemStruct $obj */
            switch ($obj->type) {
                case 1: // 诊断
                    $this->icdCollection['diagnosis'][$obj->index][$obj->code] = $obj;
                    $this->icdCodes['diagnosis'][$obj->index][] = $obj->code;
                    break;
                case 2: //手术或操作
                    $this->icdCollection['procedure'][$obj->index][$obj->code] = $obj;
                    $this->icdCodes['procedure'][$obj->index][] = $obj->code;
                    break;
                default:
                    // 跳过
                    break;
            }
        }
        return $this;
    }
    /**
     * 获取ICD春编码数组
     *
     * @return array
     */
    public function getICDCodes(): array
    {
        return $this->icdCodes;
    }
}
