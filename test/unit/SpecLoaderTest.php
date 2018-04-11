<?php

use PHPUnit\Framework\TestCase;
use OpenApi\SpecLoader;


final class SpecLoaderTest extends TestCase
{
    public function testLoadOpenApiSpec(): void
    {
        new SpecLoader();
    }
}
