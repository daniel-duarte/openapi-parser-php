<?php

use PHPUnit\Framework\TestCase;
use OpenApi\SpecLoader;


final class SpecLoaderTest extends TestCase
{
    public function testLoadOpenApiSpec(): void
    {
        $error       = false;
        $specLoader  = null;
        $openApiSpec = null;

        try {
            $specLoader = new SpecLoader();
            $openApiSpec = $specLoader->getOpenApiSpec();
        } catch (\Exception $ex) {
            $error = true;
        }

        $this->assertFalse($error, "There was an error loading the OpenApi spec.");
        $this->assertInstanceOf(\OpenSpec\Spec\OpenSpec::class, $openApiSpec);
        $this->assertEquals('OpenApi', $openApiSpec->getName());
    }
}
