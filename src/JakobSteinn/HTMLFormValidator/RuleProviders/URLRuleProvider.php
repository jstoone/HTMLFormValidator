<?php

namespace JakobSteinn\HTMLFormValidator\RuleProviders;

class URLRuleProvider extends AbstractRuleProvider
{
    /**
     * The message given on error
     * 
     * @var string
     */
    public $message = "URL is not invalid.";

    /**
     * Validate that the given data is an URL
     * 
     * @param  string   $field  The field under validation
     * @param  mixed    $data   The given field's form data
     * @return boolean
     */
    protected function validate($field, $data)
    {
        return filter_var($data, FILTER_VALIDATE_URL);
    }
}
