<?php

namespace App\Http\Requests;

use App\HelperClasses\MyApp;
use App\Rules\FileMediaRule;
use App\Rules\TextRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    public string $rule = "required";

    /**
     * @return bool
     */
    public function isUpdatedRequest(): bool
    {
        $Final = false;
        $routeName = is_null($this->route()) ? "" : $this->route()->getName();
        if (!is_null($routeName)){
            $Final = is_numeric(strpos($routeName, "update")) || is_numeric(strpos($routeName, "edit"));
        }
        return request()->isMethod("PUT") || $Final;
    }

    /**
     * @description string without in TextRule Class
     * add char.. /-
     * @param bool|null $isRequired
     * @param bool|null $isNullable
     * @param null $min
     * @param null $max
     * @return array
     * @author moner khalil
     */
    public function textRule(bool $isRequired = null, bool $isNullable = null, $min = null, $max = null)
    {
        $temp_rules = [];
        if (!is_null($isRequired)) {
            $temp_rules[] = $isRequired ? "required" : "nullable";
        }
        $temp_rules[] = "string";
        $temp_rules[] = new TextRule();
        return $this->min_max_Rule($temp_rules, $min, $max);
    }

    /**
     * @description string without in TextRule Class
     * @author moner khalil
     */
    public function editorRule(bool $isRequired = null, $min = null, $max = 10000)
    {
        $temp_rules = [];
        if (!is_null($isRequired)) {
            $temp_rules[] = $isRequired ? "required" : "nullable";
        }
        $temp_rules[] = "string";
        return $this->min_max_Rule($temp_rules, $min, $max);
    }

    /**
     * @param $tempRule
     * @param $min
     * @param $max
     * @return mixed
     * @author moner khalil
     */
    private function min_max_Rule($tempRule, $min, $max): mixed
    {
        if ($min !== null && $max === null) {
            $tempRule[] = "min:" . $min;
        } elseif ($max !== null && $min === null) {
            $tempRule[] = "max:" . $max;
        } elseif ($max !== null && $min !== null) {
            $tempRule[] = "min:" . $min;
            $tempRule[] = "max:" . $max;
        }else{
            $tempRule[]="min:1";
            $tempRule[]="max:255";
        }
        return $tempRule;
    }

    public static function validationPassword(){
        return Password::min(8)
            ->letters()
            ->mixedCase()
            ->numbers()
            ->symbols()
            ->uncompromised();
    }
}
