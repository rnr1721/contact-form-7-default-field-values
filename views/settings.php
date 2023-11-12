<h1 class="wp-heading-inline"><?php echo __('Fields that allow be used with default values in contact-form-7', 'cf7dfv'); ?></h1>

<div class="wrap">
    <div id="crud-form" class="metabox-holder">
        <div class="postbox">
            <h2 class="cf7dfv_hndle"><span><?php echo __('list of fields', 'cf7dfv'); ?></span></h2>
            <div class="cf7dfv_inside">
                <input type="text" id="cf7dfv_item-name" maxlength="20" placeholder="<?php echo __('Field name', 'cf7dfv'); ?>">
                <button id="cf7dfv_btn_add_item" class="button-primary"><?php echo __('Add', 'cf7dfv'); ?></button>

                <ul id="cf7dfv_item-list"></ul>
            </div>
        </div>
    </div>
</div>

<div class="wrap">
    <div class="metabox-holder">
        <div class="postbox">
            <h2 class="cf7dfv_hndle"><span><?php echo __('How it use', 'cf7dfv'); ?></span></h2>
            <div class="cf7dfv_inside">
                <p>
                    <?php echo __('This plugin allows you to set default field values in shortcodes. For example:', 'cf7dfv'); ?>
                </p>
                <code>[contact-form-7 id="916feaa" title="Contact form 1" your-name="John Doe" your-subject="my subject"]</code>
                <p>
                    <?php echo __('After it, you can use in form template in contact-form-7:', 'cf7dfv'); ?>
                </p>
                <code>&lt;label&gt; Your name
                    [text* your-name autocomplete:name default:shortcode_attr] &lt;/label&gt;
                </code>
                <p>
                    <?php echo __('It important! In contact-form-7 form must be present parameter', 'cf7dfv'); ?> default:shortcode_attr
                </p>
                <p>
                    <?php echo __('Add the fields you use in contact form 7. You should add those fields that you will use as parameters for shortcodes.', 'cf7dfv'); ?>
                </p>
                <h3><?php echo __('Use in code', 'cf7dfv'); ?></h3>
                <p>
                    <?php echo __('Also, you can add fields to ready-made shortcodes in the code of your themes and plugins, modifying them on the fly. For example:', 'cf7dfv'); ?>
                </p>
                <pre>
&lt;?php
$shortcode = '[contact-form-7 id="916feaa" title="Contact form 1"]';
$params = array(
    'your-name' => 'John Doe',
    'your-subject' => 'My subject'
);
$shortcode_full = cf7_add_shortcode_params($shortcode,$params);

do_shortcode($shortcode_full);
                </pre>
                <p>
                    <?php echo __('Or if you only need to insert one parameter, you can use this syntax:', 'cf7dfv'); ?>
                </p>
                <pre>
&lt;?php
$shortcode = '[contact-form-7 id="916feaa" title="Contact form 1"]';
$shortcode_full = cf7_add_shortcode_params($shortcode,'your-name:John Doe');

do_shortcode($shortcode_full);
                </pre>
                <h3><?php echo __('How check if shortcode parameter present?', 'cf7dfv'); ?></h3>
                <pre>
&lt;?php
$shortcode = '[contact-form-7 id="916feaa" title="Contact form 1"]';
// exist is bool
$exist = cf7_shortcode_has_param($shortcode,'title');
                </pre>
            </div>
        </div>
    </div>
</div>
