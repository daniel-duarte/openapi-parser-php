<?php

use PHPUnit\Framework\TestCase;
use OpenApi\SpecLoader;
use OpenSpec\Spec\OpenSpec;


final class SpecLoaderTest extends TestCase
{
    public function testLoadOpenApiSpec(): void
    {
        $error       = false;
        $specLoader  = null;
        $openApiSpec = null;

        try {
            $specLoader = new SpecLoader();
            $openApiMetaSpec = $specLoader->getOpenApiSpec();
        } catch (\Exception $ex) {
            $error = true;
        }

        $this->assertFalse($error, "There was an error loading the OpenApi spec.");
        $this->assertInstanceOf(OpenSpec::class, $openApiMetaSpec);
        $this->assertEquals('OpenApi', $openApiMetaSpec->getName());
    }
}
