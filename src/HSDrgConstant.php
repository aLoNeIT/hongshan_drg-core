<?php

declare(strict_types=1);

namespace hsdrg;

/**
 * 异常类
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class HSDrgConstant
{
    /**
     * IDC疾病编码类型映射
     */
    public const IDC_TYPE_MAP = [
        1 => 'diagnosis',
        2 => 'procedure'
    ];
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
    public const ADRG_PROCESSOR_MAP = [
        1 => \hsdrg\processor\adrg\SinglePrincipalDiagnosis::class,
        2 => \hsdrg\processor\adrg\SingleMajorProcedure::class,
        3 => \hsdrg\processor\adrg\TwoProcedure::class,
        4 => \hsdrg\processor\adrg\SinglePrincipalDiagnosisAndMultiProcedure::class,
        5 => \hsdrg\processor\adrg\SingleDiagnosis::class,
        6 => \hsdrg\processor\adrg\AnyProcedure::class,
        7 => \hsdrg\processor\adrg\ExcludeProcedure::class,
        8 => \hsdrg\processor\adrg\NoProcedure::class,
        9 => \hsdrg\processor\adrg\SinglePrincipalDiagnosisOrMajorProcedure::class,
        10 => \hsdrg\processor\adrg\SinglePrincipalDiagnosisAndTwoProcedure::class,
        11 => \hsdrg\processor\adrg\SinglePrincipalDiagnosisAndMajorProcedure::class,
    ];

    /**
     * 处理器类型映射
     * 
     * - 1：无要求(A)
     * - 2：单主诊断
     * - 3：单诊断
     * - 4：所有诊断匹配两组
     */
    public const MDC_PROCESSOR_MAP = [
        1 => \hsdrg\processor\mdc\None::class,
        2 => \hsdrg\processor\mdc\SinglePrincipalDiagnosis::class,
        3 => \hsdrg\processor\mdc\SingleDiagnosis::class,
        4 => \hsdrg\processor\mdc\TwiceDiagnosis::class,
    ];
}
