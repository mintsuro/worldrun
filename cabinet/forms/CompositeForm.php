<?php

namespace cabinet\forms;

use yii\base\Model;
use yii\helpers\ArrayHelper;

abstract class CompositeForm extends Model
{
    /**
     * @var Model[]|array[]
     */
    private $forms = [];

    abstract protected function internalForms(): array;

    public function load($data, $formName = null): bool
    {
        $success = parent::load($data, $formName);
        foreach($this->forms as $name => $value){
            if(is_array($value)){
                $success = Model::loadMultiple($value, $data, $formName === null ? null : $name) && $success;
            } else {
                $success = $value->load($data, $formName !== '' ? null : $name) && $success;
            }
        }
        return $success;
    }

    public function validate($attributeNames = null, $clearErrors = true): bool
    {
        $parentNames = $attributeNames !== null ? array_filter((array)$attributeNames, 'is_string') : null;
        $success = parent::validate($parentNames, $clearErrors);
        foreach($this->forms as $name => $value){
            if(is_array($value)){
                $success = ModeL::validateMultiple($value) && $success;
            } else {
                $innerNames = $attributeNames !== null ? ArrayHelper::getValue($attributeNames, $name) : null;
                $success = $value->validate($innerNames ?: null, $clearErrors) && $success;
            }
        }
        return $success;
    }

    public function hasErrors($attribute = null): bool
    {
        if($attribute !== null){
            return parent::hasErrors($attribute);
        }
        if(parent::hasErrors($attribute)){
            return true;
        }
        foreach($this->forms as $name => $form){
            if(is_array($form)){
                foreach($form as $i => $item){
                    if($item->hasErrors()){
                        return true;
                    }
                }
            } else {
                if($form->hasErrors()){
                    return true;
                }
            }
        }
        return false;
    }

    public function getFirstErrors(){
        $errors = parent::getFirstErrors();
        foreach($this->forms as $name => $value){
            if(is_array($value)){
                foreach($value as $i => $item){
                    foreach($item->getFirstErrors() as $attribute => $error){
                        $errors[$name . '.' . $i . '.' . $attribute] = $error;
                    }
                }
            } else {
                foreach ($value->getFirstErrors() as $attribute => $error){
                    $errors[$name . '.' . $attribute] = $error;
                }
            }
        }
        return $errors;
    }

    public function __set($name, $value){
        if(in_array($name, $this->internalForms(), true)){
            $this->forms[$name] = $value;
        }else{
            parent::__set($name, $value);
        }
    }

    public function __get($name)
    {
        if(isset($this->forms[$name])){
            return $this->forms[$name];
        }
        return parent::__get($name);
    }

    public function __isset($name)
    {
        return isset($this->forms[$name]) || parent::__isset($name);
    }
}