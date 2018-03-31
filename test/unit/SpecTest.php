<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;
use GenericEntity\Spec\ArraySpec;
use GenericEntity\Spec\ObjectSpec;


final class SpecTest extends TestCase
{
    public function runTestSpecs($dirpath): void
    {
        $specFilesExpr = $dirpath . '*.spec.yml';
        $specFiles = glob($specFilesExpr);

        foreach ($specFiles as $specFilepath) {

            $specSampleFilepath = str_replace('.spec', '', $specFilepath);

            $userSpecData       = Yaml::parseFile($specFilepath);
            $userSpecSampleData = Yaml::parseFile($specSampleFilepath);

            try {

                $userSpec = \OpenSpec\SpecBuilder::getInstance()->build($userSpecData);

                $specErrors = [];
            } catch (\OpenSpec\ParseSpecException $ex) {
                $specErrors = $ex->getErrors();
            }

            $specErrors = array_column($specErrors, 1);
            $this->assertTrue(count($specErrors) === 0, "User spec not valid in file '$specSampleFilepath':" . PHP_EOL .
                '- ' . implode(PHP_EOL . '- ', $specErrors)
            );

            if (count($specErrors) === 0) {
                $errors = $userSpec->validateGetErrors($userSpecSampleData);
                $this->assertTrue(count($errors) === 0, "User data in file '$specSampleFilepath' does not follow the spec in file '$specFilepath'." . PHP_EOL .
                    '- ' . implode(PHP_EOL . '- ', array_column($errors, 1)));
            }
        }
    }

    public function testBasicSpecs()
    {
        $this->runTestSpecs(__DIR__ . '/cases/specs/');
    }

    public function testOpenApiSpecs()
    {
        $this->runTestSpecs(__DIR__ . '/cases/openapi/');
    }
}
