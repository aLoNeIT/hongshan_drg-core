<?php

declare(strict_types=1);

namespace hsdrg\struct;

use hsdrg\interfaces\IChildCollection;
use hsdrg\interfaces\IDRGProcessor;
use hsdrg\trait\ChildCollection;
use hsdrg\Util;

/**
 * 国家医疗保障疾病诊断相关分组规则集合
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class ChsDrgSet extends Base implements IChildCollection, IDRGProcessor
{

    use ChildCollection;

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
     * @var MedicareDiagnosisRelatedGroup[]
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
        $this->childClass = MajorDiagnosticCategory::class;
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
            $this->mccItems[$mcc->code] = $mcc;
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
            $this->ccItems[$cc->code] = $cc;
            $this->ccCodeItems[] = $cc->code;
        }
        return $this;
    }
    /**
     * 载入医疗保险drg付费分组
     *
     * @param array $data drg数据
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
        $jResult = Util::jerror(2);
        // 西安drg特殊处理，后期有更多地区后再按照驱动模式单独列出
        if ($medicalRecord->inDays <= 1 || $medicalRecord->inDays > 60) {
            // 住院天数极端值病例暂不纳入DRG付费，按项目付费。住院天数极端值病例是指住院天数小于等于1天或大于60天的病例
            return Util::jerror(100);
        }
        // 匹配到的qy组结果存入该数组
        $qyResult = [];
        // 依次循环mdc，如果当前mdc的入组规则匹配，则返回当前mdc的adrg
        /** @var MajorDiagnosticCategory $mdc */
        foreach ($this->children as $mdc) {
            $jResult = $mdc->process($medicalRecord);
            $state = Util::getJState($jResult);
            // 只有未匹配到的时候才继续循环，其他情况都中断返回
            if (11 === $state) {
                continue;
            } elseif (12 === $state) {
                // 如果进入QY组，不直接退出，而是继续匹配下去
                $qyResult[] = $jResult;
                continue;
            }
            break;
        }
        // 根据返回值进行不同处理
        switch (Util::getJState($jResult)) {
            case 0: // 成功，不处理
                break;
            case 11: // 全部mdc都无法入组
                // 判断是否存在qy组数据，如果存在，则返回该数据
                if (!empty($qyResult)) {
                    return $qyResult[0];
                }
                return Util::jerror(16, '0000');
            default: // 其他错误
                return $jResult;
        }
        // 如果匹配到了，则继续匹配严重并发症或合并症
        $code = Util::getJMsg($jResult);
        $data = Util::getJData($jResult);
        $ccCode = null;
        $drgItem = null;
        $ccData = null;
        // 依次匹配mcc、cc，若mcc生成的drg编码不存在，则还需要匹配cc
        // 匹配mcc
        $jResult = $this->detectCC($code, $medicalRecord);
        if (Util::isSuccess($jResult)) {
            $ccCode = '1';
            // $drgItem = Util::getJMsg($jResult);
            $ccData = \array_merge([
                'type' => 'mcc'
            ], Util::getJData($jResult));
        } else {
            // 匹配cc
            $jResult = $this->detectCC($code, $medicalRecord, false);
            if (Util::isSuccess($jResult)) {
                $ccCode = '3';
                // $drgItem = Util::getJMsg($jResult);
                $ccData = \array_merge([
                    'type' => 'cc'
                ], Util::getJData($jResult));
            }
        }
        // 根据确定起始的最后一位编码，决定从哪个下标起始
        $arr = ['1', '3', '5', '9'];
        $idx = \is_null($ccCode) ? 2 : \array_search($ccCode, $arr);
        for ($i = $idx; $i < count($arr); $i++) {
            $result = $this->detectDrgCode($code, $arr[$i]);
            if (!\is_null($result)) {
                $drgItem = $result;
                break;
            }
        }
        // 没有找到合适的drg分组编码，返回错误信息
        if (\is_null($drgItem)) {
            return Util::jerror(10, $code);
        }
        // 成功生成有效的drg编码
        return Util::jsuccess($drgItem->code, [
            'drg' => [
                'code' => $drgItem->code,
                'name' => $drgItem->name,
                'weight' => $drgItem->weight,
                'type' => $drgItem->type,
            ],
            'mdc' => [
                'code' => $data['code'],
                'name' => $data['name']
            ],
            'adrg' => [
                'code' => $data['adrg']['code'],
                'name' => $data['adrg']['name']
            ],
            'cc' => $ccData
        ]);
    }
    /**
     * 检测drg编码是否存在
     *
     * @param string $code mdc和adrg编码
     * @param string $ccCode 并发症编码，1、3、5、9
     * @return MedicareDiagnosisRelatedGroup|null 返回有效的drg对象，不存在则返回null
     */
    protected function detectDrgCode(string $code, string $ccCode): ?MedicareDiagnosisRelatedGroup
    {
        $drgCode = "{$code}{$ccCode}";
        return $this->drgItems[$drgCode] ?? null;
    }
    /**
     * 检测严重并发症或合并症
     *
     * @param MedicalRecord $medicalRecord 病案信息
     * @param string $drgCode 匹配到的drg编码前三位
     * @param boolean $mccItemd 是否匹配mcc
     * @return array 返回一个JsonTable格式的数组，成功msg节点有效的drg编码，data是诊断数据
     */
    protected function detectCC(string $drgCode, MedicalRecord $medicalRecord, bool $mccItemed = true): array
    {
        // 根据mccItemed参数判断是匹配mcc还是cc
        if ($mccItemed) {
            $ccItems = $this->mccItems;
            $ccCodeItems = $this->mccCodeItems;
        } else {
            $ccItems = $this->ccItems;
            $ccCodeItems = $this->ccCodeItems;
        }
        // 获取交集
        $codes = \array_intersect($medicalRecord->secondaryDiagnosis, $ccCodeItems);
        // 交集为空，说明次要诊断中不存在匹配的并发症或合并症，无需后续操作
        if (empty($codes)) {
            return Util::jerror(13);
        }
        // 交集不为空，说明次要诊断中存在匹配的并发症或合并症，此时依次判断主诊断是否在排除表内
        $diagnosis = null;
        $diagnosisData = null;
        foreach ($codes as $code) {
            /** @var MajorComplicationComorbidity $cc */
            $cc = $ccItems[$code];
            $excludeGroupCode =  $cc->excludeGroupCode;
            // 如果排除表内不存在当前主诊断，说明当前次要诊断符合要求，直接返回
            $excludeGroup = $this->ccExcludeItems[$excludeGroupCode] ?? [];
            if (!isset($excludeGroup[$medicalRecord->principalDiagnosis])) {
                $diagnosis = $code;
                $diagnosisData = [
                    'code' => $cc->code,
                    'name' => $cc->name
                ];
                break;
            }
        }
        // 校验完毕，diagnosis为null，说明主诊断在排除表内，不符合要求
        return \is_null($diagnosis)
            ? Util::jerror(14)
            : Util::jsuccess($diagnosis, $diagnosisData);
    }
}
