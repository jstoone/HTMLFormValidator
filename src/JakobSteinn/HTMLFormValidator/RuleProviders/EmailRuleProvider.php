<?php

namespace JakobSteinn\HTMLFormValidator\RuleProviders;

class EmailRuleProvider extends AbstractRuleProvider
{
    /**
     * The message given on error
     * 
     * @var string
     */
    public $message = "Email is invalid.";


    /**
     * Validate that the given data is an email
     * 
     * @param  string   $field  The field under validation
     * @param  mixed    $data   The given field's form data
     * @return boolean
     */
    protected function validate($field, $data)
    {
        return filter_var($data, FILTER_VALIDATE_EMAIL);
    }
}
