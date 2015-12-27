<?php
namespace JakobSteinn\HTMLFormValidator;

use \DOMElement;
use \DOMDocument;
use JakobSteinn\HTMLFormValidator\RuleProviders\URLRuleProvider;
use JakobSteinn\HTMLFormValidator\RuleProviders\EmailRuleProvider;
use JakobSteinn\HTMLFormValidator\Exceptions\UnknownValidationRule;

class Validator
{
    /**
     * The document that will be searched for a form
     * 
     * @var DOMDocument
     */
    protected $formDocument;

    /**
     * The rule providers used to validate input
     * 
     * @var JakobSteinn\HTMLFormValidator\RuleProviders\AbstractRuleProvider
     */
    protected $ruleProviders = [
        'email'        => EmailRuleProvider::class,
        'url'            => URLRuleProvider::class,
    ];

    /**
     * List of rules to check
     * 
     * @var array
     */
    protected $rules = [];

    /**
     * List of errors from validation
     *
     * @var array
     */
    protected $errors = [];

    /**
     * Constructor
     * 
     * @param string $formHTML
     */
    public function __construct($formHTML)
    {
        $this->formDocument = new DOMDocument();
        $this->formDocument->loadHTML($formHTML);
        $this->parseForRules();
    }

    /**
     * Loop the form data through form rules
     * 
     * @param  array  $formData		The associated form data
     * @throws JakobSteinn\HTMLFormValidator\Exceptions\UnknownValidationRule
     * @return JakobSteinn\HTMLFormValidator\Validator
     */
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

    /**
     * Determine whether the validation has passed, returns true
     * if no errors, else it returns appropriate errors.
     * 
     * @return bool|array
     */
    public function passes()
    {
        if (count($this->errors) > 0) {
            return $this->errors;
        }

        return true;
    }

    /**
     * Determine whether the validation has failed, returns false
     * if it has errors, else it returns true.
     * 
     * @return bool|array
     */
    public function fails()
    {
        if (count($this->errors) > 0) {
            return true;
        }

        return $this->errors;
    }

    /**
     * Get the rules that will be applied
     * 
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Determine if the given data is given, since it is required
     *
     * @param  string 	$field     The field under validation
     * @param  array 	$fieldData The data associated with the given field
     * @return void
     */
    protected function validateRequiredFor($field, $fieldData = null)
    {
        if ($fieldData != null) {
            return;
        }

        $this->errors[$field] = "Field is required";
    }

    /**
     * Find the first form on page, and parse <input>-elements
     *
     * @return array
     */
    protected function parseForRules()
    {
        $form = $this->formDocument->getElementsByTagName('form')->item(0);
        $inputFields = $form->getElementsByTagName('input');

        foreach ($inputFields as $inputField) {
            $this->parseInputForRules($inputField);
        }

        return $this->rules;
    }

    /**
     * Determine if element has validator attribute, and save the given rule
     * 
     * @param  DOMElement $inputField 	An input field from a form
     * @return void
     */
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
