<?php namespace Samubra\Train\Classes;

/**
 * Created by PhpStorm.
 * User: samubra
 * Date: 17-5-18
 * Time: 下午10:17
 */

use Illuminate\Validation\Validator as BaseValidator;

class CustomValidator extends BaseValidator
{
    /**
     * 验证身份证号码格式
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return int
     */
    public function validateIdentity($attribute, $value, $parameters)
    {
        return preg_match('/(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}$)/', $value);
    }

    public function validateTelephone($attribute, $value, $parameters)
    {
        return preg_match('/^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$/',$value);
    }

}
