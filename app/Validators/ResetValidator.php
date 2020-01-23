<?php namespace App\Validators;

use Illuminate\Support\MessageBag;
use Illuminate\Validation\Validator;

class ResetValidator extends Validator {

    /**
     * Add an error message to the validator's collection of messages.
     *
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return void
     */
    protected function addError($attribute, $rule, $parameters)
    {
        $message = $this->getMessage($attribute, $rule);

        $message = $this->doReplacements($message, $attribute, $rule, $parameters);

//        $customMessage = new MessageBag();
//        $customMessage->merge(['code' => strtolower($rule.'_rule_error')]);
//        $customMessage->merge(['message' => $message]);

        $this->messages->add($attribute, $message);//dd($this->messages);
    }

}