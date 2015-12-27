<?php

namespace JakobSteinn\HTMLFormValidator\RuleProviders;

class EmailRuleProvider extends AbstractRuleProvider
{
    public $message = "Email is invalid.";

    protected function validate($field, $data)
    {
        return filter_var($data, FILTER_VALIDATE_EMAIL);
    }
}
