<?php

declare(strict_types=1);

namespace hsdrg;

use stdClass;

/**
 * 工具类
 * 
 * @author 王阮强 <wangruanqiang@hongshanhis.com>
 */
class Util
{
    /**
     * 获取JsonTable格式数组中的state值
     *
     * @param array $jtable JsonTable格式的数组
     * @return integer 返回state值
     */
    public static function getJState(array $jtable): int
    {
        return $jtable['state'];
    }
    /**
     * 获取JsonTable格式数组中的msg值
     *
     * @param array $jtable JsonTable格式的数组
     * @return string 返回msg值
     */
    public static function getJMsg(array $jtable): string
    {
        return $jtable['msg'];
    }
    /**
     * 获取JsonTable格式数组中的data值
     *
     * @param array $jtable JsonTable格式的数组
     * @return mix 返回data值
     */
    public static function getJData(array $jtable)
    {
        return $jtable['data'];
    }

    /**
     * 判断是否成功
     *
     * @param array $jtable JsonTable格式的数组
     * @return boolean 是否成功
     */
    public static function isSuccess(array $jtable): bool
    {
        return isset($jtable['state']) && 0 === $jtable['state'];
    }
    /**
     * 获取一个JsonTable格式的数组
     *
     * @param integer $state 状态码
     * @param mix $msg 简要消息
     * @param mix|null $data 扩展数据
     * @return array 返回一个JsonTable格式的数组
     */
    public static function jecho(int $state, $msg, $data = null): array
    {
        $result = \array_merge([
            'state' => $state,
            'msg' => $msg
        ], !\is_null($data) ? [
            'data' => $data
        ] : []);
        return $result;
    }
    /**
     * 获取一个成功的JsonTable格式的数组
     *
     * @param mix $msg 简要消息
     * @param mix|null $data 扩展数据
     * @return array 返回一个JsonTable格式的数组
     */
    public static function jsuccess($msg = 'success', $data = null): array
    {
        return self::jecho(0, $msg, $data);
    }
    /**
     * 获取一个失败的JsonTable格式的数组
     *
     * @param integer $state 状态码
     * @param mix $msg 简要消息
     * @param mix|null $data 扩展数据
     * @return array 返回一个JsonTable格式的数组
     */
    public static function jerror(int $state = 1, $msg = 'failed', $data = null): array
    {
        return self::jecho($state, $msg, $data);
    }
    /**
     * 获取一个异常的JsonTable格式的数组
     *
     * @param mix $msg 简要消息
     * @param mix|null $data 扩展数据
     * @param integer $state 错误码
     * @return array 返回一个JsonTable格式的数组
     */
    public static function jexception($msg = 'exception', $data = null, int $state = 1): array
    {
        return self::jecho($state, $msg, $data);
    }
    /**
     * 获取一个成功的且带有data节点的JsonTable格式的数组
     *
     * @param mix|null $data 扩展数据
     * @param mix $msg 简要消息
     * @return array 返回一个JsonTable格式的数组
     */
    public static function jdata($data, $msg = 'success'): array
    {
        return self::jecho(0, $msg, $data);
    }

    /**
     * 驼峰转下划线
     *
     * @param  string $value 待转换的驼峰字符串
     * @param  string $delimiter 分隔符
     * @return string 转换后的下划线字符串
     */
    public static function snake(string $value, string $delimiter = '_'): string
    {
        static $cache = [];
        $key = $value;

        if (isset($cache[$key][$delimiter])) {
            return $cache[$key][$delimiter];
        }

        if (!ctype_lower($value)) {
            $value = preg_replace('/\s+/u', '', ucwords($value));

            $value = self::lower(preg_replace('/(.)(?=[A-Z])/u', '$1' . $delimiter, $value));
        }

        return $cache[$key][$delimiter] = $value;
    }

    /**
     * 下划线转驼峰(首字母小写)
     *
     * @param  string $value 待转换的下划线字符串
     * @return string 转换后的驼峰字符串
     */
    public static function camel(string $value): string
    {
        static $cache = [];
        if (isset($cache[$value])) {
            return $cache[$value];
        }

        return $cache[$value] = lcfirst(self::studly($value));
    }

    /**
     * 下划线转驼峰(首字母大写)
     *
     * @param  string $value 待转换的下划线字符串
     * @return string 转换后的驼峰字符串
     */
    public static function studly(string $value): string
    {
        static $cache = [];
        $key = $value;

        if (isset($cache[$key])) {
            return $cache[$key];
        }

        $value = ucwords(str_replace(['-', '_'], ' ', $value));

        return $cache[$key] = str_replace(' ', '', $value);
    }

    /**
     * 字符串转小写
     *
     * @param  string $value 待转换的字符串
     * @return string 转换后的小写字符串
     */
    public static function lower(string $value): string
    {
        return mb_strtolower($value, 'UTF-8');
    }

    /**
     * 字符串转大写
     *
     * @param  string $value 待转换的字符串
     * @return string 转换后的大写字符串
     */
    public static function upper(string $value): string
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    /**
     * 获取当前类的公共属性
     *
     * @param object $object 待处理的对象
     * @param boolean $snaked 是否转换为下划线风格，默认false
     * @param boolean $ignoreNull 是否忽略null值，默认false
     * @return array 包含属性名和属性值的kv数组
     */
    public static function getPublicProps(object $object, bool $snaked = false, bool $ignoreNull = false): array
    {
        $props = [];
        $reflection = new \ReflectionObject($object);
        foreach ($reflection->getProperties() as $key => $value) {
            if ($value->isPublic()) {
                // 只有public类型的才需要记录
                $v = $value->getValue($object);
                if ($ignoreNull && \is_null($v)) {
                    continue;
                }
                $k = $snaked ? self::snake($value->getName()) : $value->getName();
                $props[$k] = $v;
            }
        }
        return $props;
    }
    /**
     * 根据公式做匹配检测
     *
     * @param array|object $props 属性值，如果是oebject类型，则会自动获取其公共属性
     * @param array $formula 公式数组，格式为：[属性名,操作符,值]
     * @return boolean 返回是否符合公式结果
     */
    public static function detectFormula($props, array $formula): bool
    {
        if (\is_object($props)) {
            $props = self::getPublicProps($props);
        }
        // 解构参数
        list($key, $operator, $value) = $formula;
        $key = self::snake($key);
        // 判断属性是否存在
        if (!isset($props[$key])) {
            return false;
        }
        // 进行公式计算(傻瓜方式)
        switch ($operator) {
            case '=':
                $result = $props[$key] == $value;
                break;
            case '>':
                $result = $props[$key] > $value;
                break;
            case '<':
                $result = $props[$key] < $value;
                break;
            case '>=':
                $result = $props[$key] >= $value;
                break;
            case '<=':
                $result = $props[$key] <= $value;
                break;
            case '<>':
            case '!=':
                $result = $props[$key] != $value;
                break;
            case 'in':
                $result = \in_array($props[$key], $value);
                break;
            case 'not in':
                $result = !\in_array($props[$key], $value);
                break;
            case 'between':
                $result = $props[$key] >= $value[0] && $props[$key] <= $value[1];
                break;
            case 'not between':
                $result = $props[$key] < $value[0] || $props[$key] > $value[1];
                break;
            default:
                $result = false;
                break;
        }
        return $result;
    }
    /**
     * 根据公式数组做匹配检测
     *
     * @param array|object $props 待检测的对象
     * @param array $formulas 公式数组，格式为：[[属性名,操作符,值],[属性名,操作符,值]]
     * @return boolean 返回是否符合公式结果
     */
    public static function detectFormulaArray($props, array $formulas): bool
    {
        $props = \is_object($props) ? self::getPublicProps($props) : $props;
        foreach ($formulas as $formula) {
            if (!self::detectFormula($props, $formula)) {
                return false;
            }
        }
        return true;
    }

    /**
     *获取一个类里所有用到的trait，包括父类的
     *
     * @param mixed $class 类名
     * @return array
     */
    public static function classUsesRecursive($class): array
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        $results = [];
        $classes = array_merge([$class => $class], class_parents($class));
        foreach ($classes as $class) {
            $results += static::traitUsesRecursive($class);
        }

        return array_unique($results);
    }

    /**
     * 获取一个trait里所有引用到的trait
     *
     * @param string $trait Trait
     * @return array
     */
    public static function traitUsesRecursive(string $trait): array
    {
        $traits = class_uses($trait);
        foreach ($traits as $trait) {
            $traits += static::traitUsesRecursive($trait);
        }

        return $traits;
    }
}
