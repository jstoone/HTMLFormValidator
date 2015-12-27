# HTML Form Validator

This project is based on [a tweet from @Ocramius](https://twitter.com/Ocramius/status/680817040429592576), I hope this is what he was thinking.

## Usage

```php
$formData = '
    <form action="/make-this-happen" method="post">
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

$formValidator = (new Validator($formHTML))->validate($_POST);

// There is also a fails()
if($formValidator->passes()) {
	echo "Succeed in life.";
}

```