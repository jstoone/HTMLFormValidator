<?php

namespace JakobSteinn\HTMLFormValidator\RuleProviders;

class URLRuleProvider extends AbstractRuleProvider
{
    public $message = "URL is not invalid.";

    protected function validate($field, $data)
    {
        return filter_var($data, FILTER_VALIDATE_URL);
    }
}
