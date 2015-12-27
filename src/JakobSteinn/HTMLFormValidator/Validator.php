<?php
namespace JakobSteinn\HTMLFormValidator;

use \DOMElement;
use \DOMDocument;
use JakobSteinn\HTMLFormValidator\RuleProviders\URLRuleProvider;
use JakobSteinn\HTMLFormValidator\RuleProviders\EmailRuleProvider;
use JakobSteinn\HTMLFormValidator\Exceptions\UnknownValidationRule;

class Validator
{
    protected $formDocument;

    protected $ruleProviders = [
        'email'        => EmailRuleProvider::class,
        'url'            => URLRuleProvider::class,
    ];

    protected $rules = [];

    protected $errors = [];

    public function __construct($formHTML)
    {
        $this->formDocument = new DOMDocument();
        $this->formDocument->loadHTML($formHTML);
        $this->parseForRules();
    }

    public function validate(array $formData)
    {
        foreach ($this->rules as $field => $fieldRule) {
            $currentFieldData = $formData[$field];

            if (! class_exists($this->ruleProviders[$fieldRule])) {
                throw new UnknownValidationRule('No rule defined for: ' + $field);
            }

            $currentRule = (new \ReflectionClass($this->ruleProviders[$fieldRule]))
                ->newInstance()->run($field, $currentFieldData);

            if ($currentRule->hasPassed) {
                continue;
            }

            $this->errors[$currentRule->field] = $currentRule->message;
        }

        return $this;
    }

    public function passes()
    {
        if (count($this->errors) > 0) {
            return $this->errors;
        }

        return true;
    }

    public function fails()
    {
        if (count($this->errors) > 0) {
            return true;
        }

        return $this->errors;
    }

    public function getRules()
    {
        return $this->rules;
    }

    protected function validateRequiredFor($field, $fieldData = null)
    {
        if ($fieldData != null) {
            return;
        }

        $this->errors[$field] = "Field is required";
    }

    protected function parseForRules()
    {
        $form = $this->formDocument->getElementsByTagName('form')->item(0);
        $inputFields = $form->getElementsByTagName('input');

        foreach ($inputFields as $inputField) {
            $this->parseInputForRules($inputField);
        }

        return $this->rules;
    }

    protected function parseInputForRules(DOMElement $inputField)
    {
        if (! $inputField->hasAttribute('data-validator')) {
            return;
        }

        $inputName = $inputField->getAttribute('name');
        $inputRule = $inputField->getAttribute('data-validator');

        $this->rules[$inputName] = $inputRule;
    }
}
