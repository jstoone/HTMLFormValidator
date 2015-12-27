<?php

use JakobSteinn\HTMLFormValidator\Validator;
use JakobSteinn\HTMLFormValidator\ValidatorFactory;

class ValidatorTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_looks_for_validation_rules_in_form_input_data_attributes()
    {
        $formHTML = $this->getBasicValidForm();

        $formValidator = new Validator($formHTML);

        $this->assertCount(1, $formValidator->getRules());
    }

    /** @test */
    public function it_returns_true_if_the_given_input_matches_form_rules()
    {
        $formHTML = $this->getBasicValidForm();
        $formData = [
            'email'    => 'johndoe@example.com',
            'terms-and-conditions'    => true,
        ];

        $formValidator = (new Validator($formHTML))->validate($formData);

        $this->assertTrue($formValidator->passes());
    }
    
    /** @test */
    public function it_returns_false_if_form_rules_do_not_pass()
    {
        $formHTML = $this->getBasicValidForm();
        $formData = [
            'email'    => 'johndoe@notanemail',
        ];
        $expectedValidatorResult = [
            'email' => "Email is invalid.",
        ];

        $formValidator = (new Validator($formHTML))->validate($formData);

        $this->assertEquals($expectedValidatorResult, $formValidator->passes());
    }

    /** @test */
    public function it_returns_true_if_form_rules_pass()
    {
        $formHTML = $this->getBasicValidForm();
        $formData = [
            'email'    => 'johndoe@isanemail.com',
        ];

        $formValidator = (new Validator($formHTML))->validate($formData);

        $this->assertTrue(true, $formValidator->passes());
    }

    /**
     * @test
     * @expectedException JakobSteinn\HTMLFormValidator\Exceptions\UnknownValidationRule
     */
    public function it_thows_an_exception_if_a_given_rule_is_unknown()
    {
        $formHTML = $this->getFormWithUnknownRule();
        $formData = [
            'unknown'    => '???',
        ];

        $formValidator = (new Validator($formHTML))->validate($formData);
    }

    protected function getFormWithUnknownRule()
    {
        return '
            <form action="%s" method="post">
                <label for="email">Email:</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value=""
                    data-validator="unknown"
                    required="required"
                />
                <input type="submit"/>
            </form>
        ';
    }

    protected function getBasicValidForm()
    {
        return '
			<form action="%s" method="post">
				<label for="email">Email:</label>
				<input
					type="email"
					id="email"
					name="email"
					value=""
					data-validator="email"
					required="required"
				/>
				<input type="submit"/>
			</form>
		';
    }
}
