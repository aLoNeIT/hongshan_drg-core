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
class Driver implements IDRGProcessor
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
    /** @inheritDoc */
    public function process(MedicalRecord $medicalRecord): array
    {
        if (\is_null($this->chsDrgSet)) {
            return $this->jcode(2);
        }
        $result = $this->chsDrgSet->process($medicalRecord);
        return $this->jcode(Util::getJState($result), Util::getJMsg($result));
    }
    /**
     * 根据错误码获取JsonTable格式信息
     *
     * @param integer $code 错误码
     * @return array
     */
    protected function jcode(int $code, ?string $msg = null): array
    {
        return Util::jerror(
            $code,
            $this->errCode[(string)$code] ?? '系统异常'
                . ($msg ? "[{$msg}]" : '')
        );
    }
}
