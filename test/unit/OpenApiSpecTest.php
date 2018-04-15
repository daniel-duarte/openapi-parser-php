<?php

use PHPUnit\Framework\TestCase;
use OpenApi\SpecLoader;
use OpenSpec\Spec\OpenSpec;


final class OpenApiSpecTest extends TestCase
{
    public function testLoadOpenApiSpec(): void
    {
        $specLoader = new SpecLoader();

        $specFilepath = __DIR__ . '/cases/pet-store.yml';

        $apiSpec = $specLoader->loadApiSpecFromYamlFile($specFilepath);

        $error = false;
        try {
            $apiSpec->getInfo();
        } catch (\Exception $ex) {
            $error = true;
        }

        $this->assertFalse($error, "There was an error getting the API info.");
    }
}
