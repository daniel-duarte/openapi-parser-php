<?php

use PHPUnit\Framework\TestCase;
use OpenSpec\SpecBuilder;
use OpenSpec\Spec\ObjectSpec;
use OpenSpec\Spec\Spec;
use OpenSpec\ParseSpecException;


final class ObjectParsingTest extends TestCase
{
    protected function getSpecInstance(): Spec
    {
        $specData = ['type' => 'object', 'fields' => 1];
        $spec = SpecBuilder::getInstance()->build($specData);

        return $spec;
    }

    public function testParseSpecResult()
    {
        $spec = $this->getSpecInstance();

        $this->assertInstanceOf(ObjectSpec::class, $spec);
    }

    public function testSpecCorrectTypeName()
    {
        $spec = $this->getSpecInstance();

        $this->assertEquals($spec->getTypeName(), 'object');
    }

    public function testSpecRequiredFields()
    {
        $spec = $this->getSpecInstance();

        $fields = $spec->getRequiredFields();
        sort($fields);
        $this->assertEquals($fields, ['fields', 'type']);
    }

    public function testSpecOptionalFields()
    {
        $spec = $this->getSpecInstance();

        $fields = $spec->getOptionalFields();
        sort($fields);
        $this->assertEquals($fields, ['extensible']);
    }

    public function testSpecAllFields()
    {
        $spec = $this->getSpecInstance();

        $reqFields = $spec->getRequiredFields();
        $optFields = $spec->getOptionalFields();
        $allFieldsCalculated = array_unique(array_merge($reqFields, $optFields));
        sort($allFieldsCalculated);

        $allFields = $spec->getAllFields();
        sort($allFields);

        $this->assertEquals($allFieldsCalculated, $allFields);
    }

    public function testMissingRequiredFields()
    {
        $specData = ['type' => 'object'];

        $exception = null;
        try {
            SpecBuilder::getInstance()->build($specData);
        } catch (ParseSpecException $ex) {
            $exception = $ex;
        }

        $this->assertTrue($exception->containsError(ParseSpecException::CODE_MISSING_REQUIRED_FIELD));
    }

    public function testUnexpectedFields()
    {
        $specData = [
            'type' => 'object',
            'this_is_an_unexpected_field' => 1234,
            'and_this_is_other' => ['a', 'b']
        ];

        $exception = null;
        try {
            SpecBuilder::getInstance()->build($specData);
        } catch (ParseSpecException $ex) {
            $exception = $ex;
        }

        $this->assertTrue($exception->containsError(ParseSpecException::CODE_UNEXPECTED_FIELDS));
    }
}
