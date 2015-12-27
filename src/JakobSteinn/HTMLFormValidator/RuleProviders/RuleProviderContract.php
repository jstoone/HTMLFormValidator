<?php

namespace JakobSteinn\HTMLFormValidator\RuleProviders;

interface RuleProviderContract
{
    public function run($field, $data);
}
