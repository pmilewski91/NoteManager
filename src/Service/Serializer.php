<?php

namespace App\Service;

use ReflectionClass;

class Serializer
{
    /**
     * [Deserializes an array into an object of the specified class.]
     *
     * @param array $data
     * @param string $className
     * 
     * @return object
     * 
     */
    public static function deserialize(array $data, string $className): object
    {
        if (!class_exists($className)) {
            throw new \InvalidArgumentException("Class $className does not exist.");
        }

        $reflection = new ReflectionClass($className);
        $constructor = $reflection->getConstructor();
        
        if ($constructor) {
            $params = [];
            foreach ($constructor->getParameters() as $param) {
                $paramName = $param->getName();
                $snakeCaseName = strtolower(preg_replace('/[A-Z]/', '_$0', $paramName));
                $params[] = $data[$snakeCaseName] ?? ($param->isOptional() ? $param->getDefaultValue() : null);
            }
            return $reflection->newInstanceArgs($params);
        }

        return new $className();
    }

    /**
     * [Serializes an object into an associative array with snake_case keys.]
     *
     * @param object $object
     * 
     * @return array
     * 
     */
    public static function serialize(object $object): array
    {
        $data = [];
        $reflection = new ReflectionClass($object);
        $properties = $reflection->getProperties();

        foreach ($properties as $property) {
            $property->setAccessible(true);
            $propertyName = $property->getName();
            $snakeCaseName = strtolower(preg_replace('/[A-Z]/', '_$0', $propertyName));
            $data[$snakeCaseName] = $property->getValue($object);
        }

        return $data;
    }
}