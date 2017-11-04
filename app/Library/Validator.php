<?php
/**
 * Class Validator
 * author salamander
 * usage example
 * $validator = new Validator(['code'=> $code, 'mobile' => $mobile]);
 *  // 设置规则和别名
 * $validator->setRules([
 *      'code'=> 'required|size:4',
 *      'mobile' => 'required|mobile',
 *      ], [
 *      'code' => '验证码',
 *      'mobile' => '手机号',
 *  ]);
 * $validator->validate();
 * 表单验证类
 */

namespace App\Library;

class Validator {

    /**
     * 待校验数据
     * @var array
     */
    private $data;

    /**
     * 校验规则
     * @var array
     */
    private $ruleList = null;

    /**
     * 校验结果
     * @var bool
     */
    private $result = null;

    /**
     * 存储字段别名
     * @var array
     */
    private $aliases = array();

    /**
     * 校验数据信息，存储错误信息
     * @var array
     */
    private $resultInfo = array();

    /**
     * 构造函数
     * @param array $data 待校验数据
     */
    public function __construct($data = null)
    {
        if ($data) {
            $this->data = $data;
        }
    }

    /**
     * 设置校验规则
     * @param $rules
     */
    public function setRules($rules, $aliases)
    {
        $this->ruleList = $rules;
        $this->aliases = $aliases;
    }

    /**
     * 检验数据
     * @return bool
     */
    public function validate()
    {
        $result = true;

        /* 如果没有设置校验规则直接返回 true */
        if ($this->ruleList === null || !count($this->ruleList)) {
            return $result;
        }

        /* 已经设置规则，则对规则逐条进行校验 */
        foreach ($this->ruleList as $varName => $rule) {
            $items = array_filter(explode('|', $rule));
            foreach ($items as $item) {
                if (strpos($item, ":") !== FALSE) {
                    $subitems = explode(":", $item);
                    if (method_exists($this, $subitems[0])) {
                        $method = $subitems[0];
                        $tmpResult = $this->$method($varName, $subitems[1]);
                        if (!$tmpResult) {
                            $result = false;
                        }
                    }
                } else {
                    if (method_exists($this, $item)) {
                        $tmpResult = $this->$item($varName);
                        if (!$tmpResult) {
                            $result = false;
                        }
                    }
                }

            }
        }
        return $result;
    }

    /**
     * 获取校验结果数据
     * @return array [description]
     */
    public function getResultInfo()
    {
        return $this->resultInfo;
    }

    /**
     * 获取第一条错误信息
     * @return mixed
     */
    public function getFirstErr() {
        foreach ($this->resultInfo as $info) {
            return $info[0];
        }
        return '';
    }

    /**
     * 校验必填参数
     * @param  string $varName 校验项
     * @return bool
     */
    private function required($varName)
    {
        if (is_array($this->data) && isset($this->data[$varName])) {
            return true;
        }
        $this->resultInfo[$varName][] = $this->aliases[$varName] . '不能为空';
        return false;
    }

    /**
     * 校验参数长度
     *
     * @param  string $varName 校验项
     * @param  string $lengthData
     * @return bool
     */
    private function between($varName, $lengthData)
    {
        $result = true;
        $data = explode(",", $lengthData);
        $minLen = $data[0];
        $maxLen = $data[1];
        /* 如果该项没有设置，默认为校验不通过 */
        if ($this->required($varName)) {
            $varLen = mb_strlen($this->data[$varName]);
            if ($varLen < $minLen || $varLen > $maxLen) {
                $result = false;
            }
        } else {
            $result = false;
        }
        if(!$result) {
            $this->resultInfo[$varName][] = $this->aliases[$varName] .
                "长度必须在{$minLen}位和{$maxLen}位之间";
        }
        return $result;
    }

    /**
     * 校验字符串长度
     * @param  string $varName 校验项
     * @return bool
     */
    private function size($varName, $size) {
        $result = true;
        /* 如果该项没有设置，默认为校验不通过 */
        if ($this->required($varName)) {
            $varLen = mb_strlen(trim($this->data[$varName]));
            if($varLen != $size) {
                $result = false;
            }
        } else {
            $result = false;
        }
        if(!$result) {
            $this->resultInfo[$varName][] = $this->aliases[$varName] . "的长度应为{$size}位";
        }
        return $result;
    }


    /**
     * 校验邮件
     * @param  string $varName 校验项
     * @return bool
     */
    private function email($varName)
    {
        $result = true;
        /* 如果该项没有设置，默认为校验不通过 */
        if ($this->required($varName)) {
            $email = trim($this->data[$varName]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $result = false;
            }
        } else {
            $result = false;
        }
        if(!$result) {
            $this->resultInfo[$varName][] = $this->aliases[$varName] . '不是有效的邮箱地址';
        }
        return $result;
    }

    /**
     * 校验手机
     * @param  string $varName 校验项
     * @return bool
     */
    private function mobile($varName)
    {
        $result = true;
        /* 如果该项没有设置，默认为校验不通过 */
        if ($this->required($varName)) {
            $mobile = trim($this->data[$varName]);
            if (!preg_match('/^1[34578]\d{9}$/', $mobile)) {
                $result = false;
            }
        } else {
            $result = false;
        }
        if(!$result) {
            $this->resultInfo[$varName][] = $this->aliases[$varName] . '不合法';
        }
        return $result;
    }

    /**
     * 校验参数为数字
     * @param  string $varName 校验项
     * @return bool
     */
    private function digit($varName)
    {
        // 注释：is_numeric：检测是否为数字字符串，可为负数和小数
        if ($this->required($varName) && is_numeric($this->data[$varName])) {
            return true;
        }
        $this->resultInfo[$varName][] = $this->aliases[$varName] . '不是数字';
        return false;
    }


    /**
     * 校验参数为整数
     * @param $varName
     * @return bool
     */
    private function integer($varName) {
        $var = $this->data[$varName];
        if ($this->required($varName) && is_numeric($var) && strpos($var, '.') === false) {
            return true;
        }
        $this->resultInfo[$varName][] = $this->aliases[$varName] . '不是整数';
        return false;
    }

    /**
     * 校验参数为正整数
     * @param $varName
     * @return bool
     */
    private function positive_integer($varName) {
        $var = $this->data[$varName];
        if ($this->required($varName) && ctype_digit($var) && intval($var) > 0) {
            return true;
        }
        $this->resultInfo[$varName][] = $this->aliases[$varName] . '不是正整数';
        return false;
    }



    /**
     * 校验参数由数字和字母组成
     * @param $varName
     * @return bool
     */
    private function alpha($varName) {
        if ($this->required($varName) && ctype_alnum($this->data[$varName])) {
            return true;
        }
        $this->resultInfo[$varName][] = $this->aliases[$varName] . '必须由数字和字母组成';
        return false;
    }

    /**
     * 校验参数与给定值相等
     * @param $varName
     * @return bool
     */
    private function same($varName, $value) {
        if ($this->required($varName) && $this->data[$varName] == $value) {
            return true;
        }
        $this->resultInfo[$varName][] = $this->aliases[$varName] . "必须和{$value}相等";
        return false;
    }


    /**
     * 校验参数为URL
     * @param  string $varName 校验项
     * @return bool
     */
    private function url($varName)
    {
        $result = true;
        /* 如果该项没有设置，默认为校验通过 */
        if ($this->required($varName)) {
            $url = trim($this->data[$varName]);
            if(!filter_var($url, FILTER_VALIDATE_URL)) {
                $result = false;
            }
        } else {
            $result = false;
        }
        if(!$result) {
            $this->resultInfo[$varName][] = $this->aliases[$varName] . "不符合url的格式";
        }
        return $result;
    }
}
?>
