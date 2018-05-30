<?php

namespace spk\scheduler\models;

use \ReflectionMethod;
use \ReflectionFunction;
use \Exception;

/**
 * Dynamic method call or functions with transfer of arguments by name
 *
 * @note Also methods (functions) with default arguments are supported
 */
class Invoker
{
    /**
     * Function call
     *
     * @param $method string Name of function
     * @param $arg array Function parameters: ['arg1' => 'value1']
     * @return mixed Work results of function
     * @throws Exception
     */
    public static function invokeFunction($method, $arg = [])
    {
        $ref = new ReflectionFunction($method);
        $params = [];
        foreach ($ref->getParameters() as $parameter) {
            if (!$parameter->isOptional() and !isset($arg[$parameter->name])) {
                throw new Exception("Missing parameter $parameter->name. Args: " . var_export($arg, true));
            }
            if (!isset($arg[$parameter->name])) {
                $params[] = $parameter->getDefaultValue();
            } else {
                $params[] = $arg[$parameter->name];
            }
        }

        return $ref->invokeArgs($params);
    }

    /**
     * Method call
     *
     * @param $object mixed Object or class
     * @param $method string Name of a method
     * @param $arg array Параметры метода: ['arg1' => 'value1']
     * @return mixed Work results of methods
     * @throws Exception
     */
    public static function invokeMethod($object, $method, $arg = [])
    {
        $ref = new ReflectionMethod($object, $method);
        $params = [];
        foreach ($ref->getParameters() as $parameter) {
            if (!$parameter->isOptional() and !isset($arg[$parameter->name])) {
                throw new Exception("Missing parameter $parameter->name. Args: " . var_export($arg, true));
            }
            if (!isset($arg[$parameter->name])) {
                $params[] = $parameter->getDefaultValue();
            } else {
                $params[] = $arg[$parameter->name];
            }
        }

        return $ref->invokeArgs(new $object(), $params);
    }
}