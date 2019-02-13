<?php

namespace OpenApi;

use OpenSpec\Entity;
use OpenSpec\Spec\OpenSpec;
use Symfony\Component\Yaml\Yaml;


// @todo Update to support OpenApi v3.0.0, v3.0.1 and v3.0.2

class SpecLoader
{
    /**
     * @var OpenSpec
     */
    protected $_openApiSpec = null;

    public function __construct()
    {
        $this->_loadOpenApiSpec();
    }

    protected function _loadOpenApiSpec()
    {
        // Load OpenApi metadata from Yaml file
        $openApiSpecData = $this->_loadYamlFile(__DIR__ . '/openapi_v3-0-0.spec.yml');

        // Analyze metadata acording to OpenSpec (create OpenSpec for OpenApi, to analize user API specs)
        $this->_openApiSpec = new OpenSpec($openApiSpecData);
    }

    protected function _loadYamlFile(string $filepath): array
    {
        $data = Yaml::parseFile($filepath);
        if ($data === null) {
            throw new \RuntimeException("Not valid Yaml format in file '$filepath'.");
        }

        return $data;
    }

    protected function _loadYamlString(string $yaml): array
    {
        $data = Yaml::parse($yaml);
        if ($data === null) {
            throw new \RuntimeException("Not valid Yaml string format.");
        }

        return $data;
    }

    public function getOpenApiSpec(): OpenSpec
    {
        return $this->_openApiSpec;
    }

    public function loadApiSpecFromYamlFile(string $filepath)
    {
        $apiSpecData = $this->_loadYamlFile($filepath);

        return $this->loadApiSpec($apiSpecData);
    }

    public function loadApiSpecFromYamlString(string $yaml)
    {
        $apiSpecData = $this->_loadYamlString($yaml);

        return $this->loadApiSpec($apiSpecData);
    }

    public function loadApiSpec(array $specData): Entity
    {
        return $this->_openApiSpec->parse($specData);
    }
}
