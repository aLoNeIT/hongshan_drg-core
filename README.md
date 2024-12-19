# hongshan_drg-core

## 主要功能
HSDrg 管理类，操作入口
Driver 驱动基类，用于规范所有具体实例的实现规范
processor 该目录下是所有处理工具，用于drg计算，下面细分mdc和adrg处理器
struct 该目录下是数据的类实现，部分类中带有逻辑运算能力

## ADRG处理器功能介绍

### 1：\hsdrg\processor\adrg\SinglePrincipalDiagnosis::class
    - 单主诊断

### 2：\hsdrg\processor\adrg\SingleMajorProcedure::class
    - 单主手术或操作

### 3：\hsdrg\processor\adrg\TwoProcedure::class
    - 双手术或操作

### 4：\hsdrg\processor\adrg\SinglePrincipalDiagnosisAndMultiProcedure::class
    - 单主诊断+手术1+手术2
    - 单主诊断+手术表1+手术表3+手术表4

### 5：\hsdrg\processor\adrg\SingleDiagnosis::class
    - 单诊断

### 6：\hsdrg\processor\adrg\AnyProcedure::class
    - 任意手术或操作

### 7：\hsdrg\processor\adrg\ExcludeProcedure::class
    - 排除手术列表外的其他任意手术匹配的处理器

### 8：\hsdrg\processor\adrg\NoProcedure::class
    - 无手术或操作

### 9：\hsdrg\processor\adrg\SinglePrincipalDiagnosisOrMajorProcedure::class
    - 包含主要诊断或主要手术或操作

### 10：\hsdrg\processor\adrg\SinglePrincipalDiagnosisAndTwoProcedure::class
    - 单主诊断+手术1+手术2

### 11：\hsdrg\processor\adrg\SinglePrincipalDiagnosisAndMajorProcedure::class
    - 包含单主诊断和单主手术

### 12：\hsdrg\processor\adrg\SinglePrincipalDiagnosisAndMultiProcedureSimple::class
    - 单主诊断+手术1 
    - 单主诊断+手术2+手术3

### 100：\hsdrg\processor\adrg\ComplexA::class
    - 主要诊断+主要手术或操作1
    - 主要手术或操作2
    - 手术或操作3+手术或操作4

### 101：\hsdrg\processor\adrg\ComplexB::class
    - 主要诊断+手术或操作1+手术或操作2
    - 主要诊断+手术或操作1+手术或操作3+手术或操作4
    - 主要诊断+手术或操作4+手术或操作5

### 102：\hsdrg\processor\adrg\ComplexC::class
    - 主要诊断1+主要手术或操作
    - 主要诊断2+其他诊断+主要手术或操作

### 103：\hsdrg\processor\adrg\ComplexD::class
    - 主要诊断+其他诊断1+主要手术或操作
    - 其他诊断2+主要手术或操作