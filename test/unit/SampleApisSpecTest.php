<?php

use PHPUnit\Framework\TestCase;
use OpenApi\SpecLoader;
use OpenSpec\ParseSpecException;


final class SampleApisSpecTest extends TestCase
{
    public function runTestSpecs($dirpath): void
    {
        $specLoader = new SpecLoader();

        $sampleFilepaths = glob($dirpath . '*.yml');

        foreach ($sampleFilepaths as $sampleFilepath) {

            $sampleApiErrors = [];
            try {
                $a = $specLoader->loadApiSpecFromYamlFile($sampleFilepath);
            } catch (ParseSpecException $ex) {
                $sampleApiErrors = $ex->getErrors();
            }

            $sampleApiErrors = array_column($sampleApiErrors, 1);
            $this->assertTrue(count($sampleApiErrors) === 0, "Api spec not valid in file '$sampleFilepath':" . PHP_EOL .
                '- ' . implode(PHP_EOL . '- ', $sampleApiErrors)
            );
        }
    }

    public function testOpenApiSpecs()
    {
        $this->runTestSpecs(__DIR__ . '/cases/');
    }
}
