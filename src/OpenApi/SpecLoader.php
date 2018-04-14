<?php

namespace OpenApi;

use OpenSpec\Spec\OpenSpec;
use Symfony\Component\Yaml\Yaml;


class SpecLoader
{
    protected $_openApiSpec = null;

    public function __construct()
    {
        $this->_loadOpenApiSpec();
    }

    protected function _loadOpenApiSpec()
    {
        // Load OpenApi metadata from Yaml file
        $openApiSpecData = $this->_loadYamlFile(__DIR__ . '/openapi.spec.yml');

        // Analyze metadata acording to OpenSpec (create OpenSpec for OpenApi, to analize user API specs)
        $this->_openApiSpec = new OpenSpec($openApiSpecData);
    }

    protected function _loadYamlFile($filepath): array
    {
        $data = Yaml::parseFile($filepath);
        if ($data === null) {
            throw new \RuntimeException("Not valid Yaml format in file '$filepath'.");
        }

        return $data;
    }

    public function getOpenApiSpec(): OpenSpec
    {
        return $this->_openApiSpec;
    }

    public function loadApiSpecFromYamlFile(string $filepath)
    {
        $apiSpecData = Yaml::parseFile($filepath);

        return $this->loadApiSpec($apiSpecData);
    }

    public function loadApiSpecFromYamlString(string $yaml)
    {
        $apiSpecData = Yaml::parse($yaml);

        return $this->loadApiSpec($apiSpecData);
    }

    public function loadApiSpec(array $specData): array
    {
        return $this->_openApiSpec->validateGetErrors($specData);
    }
}
