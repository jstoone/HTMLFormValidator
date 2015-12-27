<?php

namespace JakobSteinn\HTMLFormValidator\RuleProviders;

abstract class AbstractRuleProvider implements RuleProviderContract
{
    public $field;

    public $data;

    public $hasPassed = false;

    public $message = "";

    public function run($field, $data)
    {
        $this->field = $field;
        $this->data = $data;

        $this->hasPassed = (bool) $this->validate($field, $data);

        return $this;
    }

    abstract protected function validate($field, $data);
}
