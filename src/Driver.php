<?php

declare(strict_types=1);

namespace hsdrg;

use hsdrg\interfaces\IDRGProcessor;
use hsdrg\struct\ChsDrgSet;
use hsdrg\struct\MedicalRecord;

/**
 * drg分组驱动基类
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class Driver
{
    /**
     * drg集合
     *
     * @var ChsDrgSet
     */
    protected $chsDrgSet = [];
    /**
     * 配置信息
     *
     * @var array
     */
    protected $config = [
        'type' => '',
    ];
    /**
     * 错误码
     *
     * @var array
     */
    protected $errCode = [
        '2' => '未加载正确的DRG配置',
        '10' => '未匹配到符合要求的DRG分组',
        '11' => '不符合当前MDC入组规则',
        '12' => '未匹配到ADRG分组，进入歧义组',
        '13' => '次要诊断不存在于MCC或CC数据中',
        '14' => '主要诊断未通过MCC或CC排除表校验',
        '15' => '不符合当前ADRG入组规则',
        '16' => '未匹配到任意MDC分组',
        // 100以上业务错误
        '100' => '住院天数极端值病例暂不纳入DRG付费'
    ];

    /**
     * 构造函数
     * 
     * @param array $config 配置信息
     */
    public function __construct(array $config = [])
    {
        $this->config = \array_merge($this->config, $config);
        $this->initialize();
    }
    /**
     * 初始化函数
     *
     * @return void
     */
    protected function initialize(): void
    {
    }
    /**
     * 切换DRG集合
     *
     * @param string $code drgSet集合编码
     * @return ChsDrgSet 返回切换后的DRG集合
     */
    public function switch(string $code): ChsDrgSet
    {
        if (!isset($this->chsDrgSet[$code])) {
            $this->chsDrgSet[$code] = new ChsDrgSet();
        }
        return $this->chsDrgSet[$code];
    }
    /**
     * 加载数据
     *
     * @param array $data 数据内容，包含节点cc、mcc、cc_exclude、mdc、drg
     * @return self 返回当前实例对象
     */
    public function load(string $code, string $name, array $data): self
    {
        $this->switch($code)->load([
            'code' => $code,
            'name' => $name,
        ])->loadChildren($data['mdc'])->loadCC($data['cc'])
            ->loadMCC($data['mcc'])->loadCCExclude($data['cc_exclude'])
            ->loadDRG($data['drg']);
        return $this;
    }
    /**
     * drg分组处理
     * 
     * @param string $drgSetCode drgSet集合编码
     * @param MedicalRecord $medicalRecord 病案信息
     * @return array 返回JsonTable格式的数组数据，msg节点是匹配到的编码
     */
    public function process(string $drgSetCode, MedicalRecord $medicalRecord): array
    {
        $chsDrgSet = $this->switch($drgSetCode);
        $result = $chsDrgSet->process($medicalRecord);
        switch (Util::getJState($result)) {
            case 0: // 成功
                return $result;
            case 12: // adrg未匹配
            case 16: // mdc未匹配
                return $this->jcode(Util::getJState($result), Util::getJMsg($result), [
                    'code' => Util::getJMsg($result)
                ]);
                break;
            default:
                return $this->jcode(Util::getJState($result), Util::getJMsg($result), Util::getJData($result));
        }
    }
    /**
     * 根据状态码获取JsonTable格式信息
     *
     * @param integer $code 状态码
     * @param string $msg 简要信息
     * @param mixed $data 数据
     * @return array
     */
    protected function jcode(int $code, ?string $msg = null, $data = null): array
    {
        if (0 == $code) {
            // 成功
            return Util::jsuccess($msg, $data);
        }
        $errMsg = $this->errCode[(string)$code] ?? '系统异常';
        return Util::jerror(
            $code,
            $errMsg . (!\is_null($msg) && 'failed' != $msg ? "[{$msg}]" : ''),
            $data
        );
    }
    /**
     * 获取当前驱动的数据
     *
     * @return array
     */
    public function toArray(): array
    {
        $result = [];
        /** @var ChsDrgSet $item */
        foreach ($this->chsDrgSet as $key => $item) {
            $result[$key] = $item->toArray();
        }
        return $result;
    }
}
