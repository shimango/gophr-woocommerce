<?php

namespace Gophr\Tests\Unit;

use Gophr\Tests\TestCase;
use Gophr\Woocommerce\Admin\FormFields;

class FormFieldsTest extends TestCase
{

    public function test_get_fields_returns_array()
    {
        $fields = FormFields::getFields();
        $this->assertIsArray($fields);
        $this->assertNotEmpty($fields);
    }

    public function test_fields_include_api_key()
    {
        $fields = FormFields::getFields();
        $this->assertArrayHasKey('gophr_api_key', $fields);
    }

    public function test_api_key_field_is_text_type()
    {
        $fields = FormFields::getFields();
        $this->assertEquals('text', $fields['gophr_api_key']['type']);
    }

    public function test_fields_include_environment_setting()
    {
        $fields = FormFields::getFields();
        $this->assertArrayHasKey('gophr_environment', $fields);
    }

    public function test_environment_field_has_options()
    {
        $fields = FormFields::getFields();
        $this->assertArrayHasKey('options', $fields['gophr_environment']);
        $this->assertArrayHasKey('production', $fields['gophr_environment']['options']);
        $this->assertArrayHasKey('sandbox', $fields['gophr_environment']['options']);
    }

    public function test_enable_field_is_checkbox()
    {
        $fields = FormFields::getFields();
        $this->assertEquals('checkbox', $fields['gophr_enable']['type']);
    }

    public function test_enable_field_defaults_to_yes()
    {
        $fields = FormFields::getFields();
        $this->assertEquals('yes', $fields['gophr_enable']['default']);
    }
}