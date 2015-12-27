<?php

namespace JakobSteinn\HTMLFormValidator\RuleProviders;

abstract class AbstractRuleProvider implements RuleProviderContract
{
    /**
     * The name of the form field
     * 
     * @var string
     */
    public $field;

    /**
     * The data for given form field
     * 
     * @var mixed
     */
    public $data;

    /**
     * Whether or not the rules pass
     * 
     * @var boolean
     */
    public $hasPassed = false;

    /**
     * The message given on error
     * 
     * @var string
     */
    public $message = "";


    /**
     * Run the validation rule
     * 
     * @param  string   $field  The field under validation
     * @param  mixed    $data   The given field's form data
     * @return void
     */
    public function run($field, $data)
    {
        $this->field = $field;
        $this->data = $data;

        $this->hasPassed = (bool) $this->validate($field, $data);

        return $this;
    }

    /**
     * Validate the given field and data
     * 
     * @param  string   $field  The field under validation
     * @param  mixed    $data   The given field's form data
     * @return boolean
     */
    abstract protected function validate($field, $data);
}
