<?php namespace Samubra\Train;

use System\Classes\PluginBase;
use Samubra\Train\Classes\CustomValidator;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
      return [
            'Samubra\Train\Components\RecordForm' => 'recordForm'
        ];
    }

    public function registerSettings()
    {
    }
    public function register()
    {
        /**Validator::extend('identity', function($attribute, $value, $parameters) {
            return preg_match('/(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}$)/', $value);
        });**/
        //\Validator::resolver(function($translator, $data, $rules, $messages, $customAttributes) {
        //    return new CustomValidator($translator, $data, $rules, $messages, $customAttributes);
        //});
    }
}
