<?php

declare(strict_types=1);

namespace hsdrg\struct;

use hsdrg\interfaces\ICollection;
use hsdrg\interfaces\IDRGProcessor;
use hsdrg\trait\Collection;
use hsdrg\Util;

/**
 * 国家医疗保障疾病诊断相关分组规则集合
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class ChsDrgSet extends Base implements ICollection, IDRGProcessor
{

    use Collection;

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
     * 并发症或合并症编码集合，每个元素都是string
     *
     * @var array
     */
    protected $ccCodeItems = [];
    /**
     * 并发症或合并症集合，每个元素都是一个ComplicationComorbidity对象
     *
     * @var array
     */
    protected $ccItems = [];
    /**
     * 严重并发症或合并症编码集合，每个元素都是string
     *
     * @var array
     */
    protected $mccCodeItems = [];
    /**
     * 严重并发症或合并症集合，每个元素都是一个MajorComplicationComorbidity对象
     *
     * @var array
     */
    protected $mccItems = [];
    /**
     * 医疗保险drg付费分组集合，kv格式，k是drg编码，v是一个MedicareDiagnosisRelatedGroup对象
     *
     * @var array
     */
    protected $drgItems = [];
    /**
     * 严重并发症或合并症排除集合，kv格式，k是排除表分组编码，v是一个ComplicationComorbidityExclude对象
     *
     * @var array
     */
    protected $ccExcludeItems = [];

    /** @inheritDoc */
    protected function initialize(): void
    {
        parent::initialize();
        $this->itemClass = MajorDiagnosticCategory::class;
    }
    /**
     * 载入严重并发症或合并症
     *
     * @param array $data 严重并发症或合并症数据
     * @param boolean $clear 是否清理已有的数据，默认true
     * @return self 返回当前对象
     */
    public function loadMCC(array $data, bool $clear = true): self
    {
        if ($clear) {
            $this->mccCodeItems = [];
            $this->mccItems = [];
        }
        foreach ($data as $item) {
            $mcc = (new MajorComplicationComorbidity())->load($item);
            $this->mccItems[] = $mcc;
            $this->mccCodeItems[] = $mcc->code;
        }
        return $this;
    }
    /**
     * 载入并发症或合并症
     *
     * @param array $data 严重并发症或合并症数据
     * @param boolean $clear 是否清理已有的数据，默认true
     * @return self 返回当前对象
     */
    public function loadCC(array $data, bool $clear = true): self
    {
        if ($clear) {
            $this->ccCodeItems = [];
            $this->ccItems = [];
        }
        foreach ($data as $item) {
            $cc = (new ComplicationComorbidity())->load($item);
            $this->ccItems[] = $cc;
            $this->ccCodeItems[] = $cc->code;
        }
        return $this;
    }
    /**
     * 载入医疗保险drg付费分组
     *
     * @param array $data 严重并发症或合并症数据
     * @param boolean $clear 是否清理已有的数据，默认true
     * @return self 返回当前对象
     */
    public function loadDRG(array $data, bool $clear = true): self
    {
        $clear && $this->drgItems = [];
        foreach ($data as $item) {
            $drg = (new MedicareDiagnosisRelatedGroup())->load($item);
            $this->drgItems[$drg->code] = $drg;
        }
        return $this;
    }
    /**
     * 载入严重并发症或合并症排除集合
     *
     * @param array $data 严重并发症或合并症数据
     * @param boolean $clear 是否清理已有的数据，默认true
     * @return self 返回当前对象
     */
    public function loadCCExclude(array $data, bool $clear = true): self
    {
        $clear && $this->ccExcludeItems = [];
        foreach ($data as $item) {
            $ccExclude = (new ComplicationComorbidityExclude())->load($item);
            $this->ccExcludeItems[$ccExclude->groupCode][$ccExclude->code] = $ccExclude;
        }
        return $this;
    }

    /** @inheritDoc */
    public function process(MedicalRecord $medicalRecord): array
    {
        // 依次循环mdc，如果当前mdc的入组规则匹配，则返回当前mdc的adrg
        /** @var MajorDiagnosticCategory $mdc */
        foreach ($this->items as $mdc) {
            $jResult = $mdc->process($medicalRecord);
            $state = Util::getJState($jResult);
            // 只有未匹配到的时候才继续循环，其他情况都中断返回
            if (11 === $state) {
                continue;
            }
            break;
        }
        // 未成功，则直接返回错误
        if (!Util::isSuccess($jResult)) {
            return $jResult;
        }
        // 如果匹配到了，则继续匹配adrg的严重并发症或合并症
        $code = Util::getJMsg($jResult);
        $ccCode = null;
        // 匹配mcc
        $jResult = $this->detectCC($medicalRecord, $this->mccItems, $this->mccCodeItems);
        if (Util::isSuccess($jResult)) {
            $ccCode = '1';
        } else {
            // 匹配cc
            $jResult = $this->detectCC($medicalRecord, $this->ccItems, $this->ccCodeItems);
            if (Util::isSuccess($jResult)) {
                $ccCode = '3';
            } else {
                $state = Util::getJState($jResult);
                switch ($state) {
                    case 0: // 伴有并发症或合并症
                        $ccCode = '3';
                        break;
                    case 13: // 不伴并发症或合并症
                        $ccCode = '5';
                        break;
                    default: // 匹配失败
                        return Util::jerror(14);
                }
            }
        }
        // 生成drg编码
        $drgCode = "{$code}{$ccCode}";
        if (!isset($this->drgItems[$drgCode])) {
            // 重新生成末尾为9的编码
            $drgCode = "{$code}9";
            if (!isset($this->drgItems[$drgCode])) {
                return Util::jerror(10);
            }
        }
        // 成功生成有效的drg编码
        return Util::jsuccess($drgCode);
    }
    /**
     * 检测严重并发症或合并症
     *
     * @param MedicalRecord $medicalRecord 病案信息
     * @param array $ccItems 并发症或合并症数据集合
     * @param array $ccCodeItems 并发症或合并症编码集合
     * @return array 返回一个JsonTable格式的数组，成功msg节点是匹配到的次要诊断编码
     */
    protected function detectCC(MedicalRecord $medicalRecord, array $ccItems, array $ccCodeItems): array
    {
        // 获取交集
        $codes = \array_intersect($medicalRecord->secondaryDiagnosis, $ccCodeItems);
        // 交集为空，说明次要诊断中不存在匹配的并发症或合并症，无需后续操作
        if (empty($codes)) {
            return Util::jerror(13);
        }
        // 交集不为空，说明次要诊断中存在匹配的并发症或合并症，此时依次判断主诊断是否在排除表内
        $diagnosis = null;
        foreach ($codes as $code) {
            /** @var MajorComplicationComorbidity $cc */
            $cc = $ccItems[$code];
            $excludeGroupCode =  $cc->excludeGroupCode;
            // 如果排除表内不存在当前主诊断，说明当前次要诊断符合要求，直接返回
            $excludeGroup = $this->ccExcludeItems[$excludeGroupCode] ?? [];
            if (!isset($excludeGroup[$medicalRecord->principalDiagnosis])) {
                $diagnosis = $code;
                break;
            }
        }
        // 校验完毕，diagnosis为null，说明主诊断在排除表内，不符合要求
        return \is_null($diagnosis) ? Util::jerror(14) : Util::jsuccess($diagnosis);
    }
}
