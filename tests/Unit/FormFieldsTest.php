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

    public function test_fields_include_vehicle_type()
    {
        $fields = FormFields::getFields();
        $this->assertArrayHasKey('gophr_vehicle_type', $fields);
        $this->assertEquals('select', $fields['gophr_vehicle_type']['type']);
        $this->assertEquals('', $fields['gophr_vehicle_type']['default']);
    }

    public function test_vehicle_type_has_all_options()
    {
        $fields = FormFields::getFields();
        $options = $fields['gophr_vehicle_type']['options'];
        $this->assertArrayHasKey('', $options); // Auto
        $this->assertArrayHasKey('10', $options); // Bike
        $this->assertArrayHasKey('15', $options); // Cargo Bike
        $this->assertArrayHasKey('20', $options); // Motorbike
        $this->assertArrayHasKey('30', $options); // Car
        $this->assertArrayHasKey('40', $options); // Small Van
        $this->assertArrayHasKey('50', $options); // Large Van
    }

    public function test_fields_include_proof_options()
    {
        $fields = FormFields::getFields();
        $this->assertArrayHasKey('gophr_pickup_proof_required', $fields);
        $this->assertArrayHasKey('gophr_dropoff_proof_required', $fields);
        $this->assertEquals('select', $fields['gophr_pickup_proof_required']['type']);
        $this->assertEquals('select', $fields['gophr_dropoff_proof_required']['type']);
    }

    public function test_fields_include_age_verification()
    {
        $fields = FormFields::getFields();
        $this->assertArrayHasKey('gophr_min_required_age', $fields);
        $this->assertEquals('select', $fields['gophr_min_required_age']['type']);
        $this->assertArrayHasKey('18', $fields['gophr_min_required_age']['options']);
        $this->assertArrayHasKey('21', $fields['gophr_min_required_age']['options']);
    }

    public function test_fields_include_pin_and_id_check()
    {
        $fields = FormFields::getFields();
        $this->assertArrayHasKey('gophr_pin_required', $fields);
        $this->assertArrayHasKey('gophr_id_check', $fields);
        $this->assertEquals('checkbox', $fields['gophr_pin_required']['type']);
        $this->assertEquals('checkbox', $fields['gophr_id_check']['type']);
        $this->assertEquals('no', $fields['gophr_pin_required']['default']);
        $this->assertEquals('no', $fields['gophr_id_check']['default']);
    }

    public function test_fields_include_pickup_instructions()
    {
        $fields = FormFields::getFields();
        $this->assertArrayHasKey('gophr_pickup_instructions', $fields);
        $this->assertArrayHasKey('gophr_pickup_tips', $fields);
        $this->assertEquals('textarea', $fields['gophr_pickup_instructions']['type']);
        $this->assertEquals('textarea', $fields['gophr_pickup_tips']['type']);
    }

    public function test_fields_include_parcel_handling()
    {
        $fields = FormFields::getFields();
        $this->assertArrayHasKey('gophr_parcel_flags', $fields);
        $this->assertArrayHasKey('gophr_cold_chain', $fields);
        $this->assertEquals('parcel_flags', $fields['gophr_parcel_flags']['type']);
        $this->assertEquals('text', $fields['gophr_cold_chain']['type']);
    }
}