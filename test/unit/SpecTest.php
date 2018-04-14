<?php

use PHPUnit\Framework\TestCase;
use OpenApi\SpecLoader;


final class SpecTest extends TestCase
{
    public function runTestSpecs($dirpath): void
    {
        $specLoader = new SpecLoader();

        $sampleFilepaths = glob($dirpath . '*.yml');

        foreach ($sampleFilepaths as $sampleFilepath) {

            $sampleApiErrors = $specLoader->loadApiSpecFromYamlFile($sampleFilepath);

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
