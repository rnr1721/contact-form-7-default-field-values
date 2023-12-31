# contact-form-7-default-field-values
Wordpress plugin than can set default field values in forms, that you can set in shortcodes as parameters

## Basic usage
1. Go to plugin settings Wordpress menu -> Contact Form 7 -> Fields for default values
2. Add fields that will be used for shortcode parameters (example: your-name, your-email etc)
3. Go to Contact Form 7 and modyfy field that you need to this:

```
<label> Your name
    [text* your-name autocomplete:name default:shortcode_attr] </label>

<label> Your e-mail
    [email* your-email autocomplete:email default:shortcode_attr] </label>
```

After it you can set these field values as shortcode parameters, like this:

```
[contact-form-7 id="916feaa" title="Contact form 1" your-name="John Doe" your-email="admin@example.com"]
```

## Usage in code

Also, you can add fields to ready-made shortcodes in the code of your themes and plugins, modifying them on the fly. For example:

```php
<?php
$shortcode = '[contact-form-7 id="916feaa" title="Contact form 1"]';
$params = array(
    'your-name' => 'John Doe',
    'your-subject' => 'My subject'
);
$shortcode_full = cf7_add_shortcode_params($shortcode,$params);

do_shortcode($shortcode_full);
```

Or, if you need to use only one parameter, you can use these syntax:

```php
<?php
$shortcode = '[contact-form-7 id="916feaa" title="Contact form 1"]';
$shortcode_full = cf7_add_shortcode_params($shortcode,'your-name:John Doe');

do_shortcode($shortcode_full);
```

### How to check that some parameter present in shortcode?

```php
<?php
$shortcode = '[contact-form-7 id="916feaa" title="Contact form 1"]';
// exist is bool
$exist = cf7_shortcode_has_param($shortcode,'title');
```
