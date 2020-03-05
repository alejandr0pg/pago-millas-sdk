<?php

declare(strict_types=1);

namespace tests;

/**
 * Class MockingHelpers.
 */
class MockingHelpers
{
    /**
     * @param $object
     * @param string $propertyName
     * @param $value
     * @throws \ReflectionException
     */
    public static function mockProperty($object, string $propertyName, $value)
    {
        $reflectionClass = new \ReflectionClass($object);

        $property = $reflectionClass->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
        $property->setAccessible(false);
    }
}
