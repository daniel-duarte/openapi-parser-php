<?php

namespace OpenSpec\Spec;

use OpenSpec\ParseSpecException;


abstract class Spec
{
    public function __construct(array $specData)
    {
        $errors = $this->_validateSpecData($specData);

        if (count($errors) > 0) {
            throw new ParseSpecException('Invalid spec data.', ParseSpecException::CODE_MULTIPLE_PARSER_ERROR, $errors);
        }
    }

    public abstract function getTypeName(): string;

    public abstract function getRequiredFields(): array;

    public abstract function getOptionalFields(): array;

    public function getAllFields(): array
    {
        $reqFields = $this->getRequiredFields();
        $optFields = $this->getOptionalFields();

        return array_merge($reqFields, $optFields);
    }

    protected function _validateSpecData($specData)
    {
        $errors = [];

        // Check if $specData is an array
        if (!is_array($specData)) {
            $errors[] = [ParseSpecException::CODE_ARRAY_EXPECTED, "Expected array with spec data, '" . gettype($specData) . "' given."];
            return $errors;
        }

        // Check for required fields that are not present
        $givenFields = array_keys($specData);
        $requiredFields = $this->getRequiredFields();
        $missingRequiredFields = array_diff($requiredFields, $givenFields);
        if (count($missingRequiredFields) > 0) {
            $missingRequiredMetakeysStr = '\'' . implode('\', \'', $missingRequiredFields) . '\'';
            $errors[] = [ParseSpecException::CODE_MISSING_REQUIRED_FIELD, "Invalid spec data. Missing required field(s) $missingRequiredMetakeysStr."];
        }

        // Check for unexpected fields
        $allValidFields = $this->getAllFields();
        $unexpectedFields = array_diff($givenFields, $allValidFields);
        if (count($unexpectedFields) > 0) {
            $unexpectedFieldsStr = '\'' . implode('\', \'', $unexpectedFields) . '\'';
            $errors[] = [ParseSpecException::CODE_UNEXPECTED_FIELDS, "Invalid spec data. Unexpected field(s) $unexpectedFieldsStr."];
        }

        $validGivenFields = array_diff($givenFields, $unexpectedFields);
        $fieldsErrors = $this->_validateFieldsSpecData($specData, $validGivenFields);
        $errors = array_merge($errors, $fieldsErrors);

        return $errors;
    }

    protected function _validateFieldsSpecData($specData, $fields)
    {
        $errors = [];

        foreach ($fields as $field) {
            $fieldValidationMethodName = '_validateFieldSpecData_' . $field;
            $fieldErrors = $this->$fieldValidationMethodName($specData[$field]);
            $errors = array_merge($errors, $fieldErrors);
        }

        return $errors;
    }

    protected function _validateFieldSpecData_type($fieldValue): array
    {
        $errors = [];
        $expectedValue = $this->getTypeName();
        if ($fieldValue !== $expectedValue) {
            $errors[] = [ParseSpecException::CODE_INVALID_TYPE_NAME, "Expected 'type' field to have the value '$expectedValue', but '$fieldValue' given.'"];
        }

        return $errors;
    }
}