<?php

namespace OpenApi;

use OpenSpec\Spec\OpenSpec;
use OpenSpec\SpecLibrary;
use Symfony\Component\Yaml\Yaml;


class SpecLoader
{
    public function __construct()
    {
        $this->_loadOpenApiSpec();
    }

    protected function _loadOpenApiSpec()
    {
        // Load OpenApi metadata from Yaml file
        $openApiSpecData = $this->_loadYamlFile(__DIR__ . '/openapi.spec.yml');

        // Analyze metadata acording to OpenSpec
        $openApiSpec = new OpenSpec($openApiSpecData);

        // Register OpenApi spec
        SpecLibrary::getInstance()->registerSpec($openApiSpec->getName(), $openApiSpec);
    }

    protected function _loadYamlFile($filepath): array
    {
        $data = Yaml::parseFile($filepath);
        if ($data === null) {
            throw new \RuntimeException("Not valid Yaml format in file '$filepath'.");
        }

        return $data;
    }
}
