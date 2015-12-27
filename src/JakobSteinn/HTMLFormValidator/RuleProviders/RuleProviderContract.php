<?php

namespace JakobSteinn\HTMLFormValidator\RuleProviders;

interface RuleProviderContract
{
    /**
     * Run the validation rule
     * 
     * @param  string 	$field 	The field under validation
     * @param  mixed 	$data  	The given field's form data
     * @return void
     */
    public function run($field, $data);
}
