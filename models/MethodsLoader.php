<?php

namespace spk\scheduler\models;

use hanneskod\classtools\Iterator\ClassIterator;
use Symfony\Component\Finder\Finder;
use yii;
use yii\base\Model;

class MethodsLoader extends Model
{
    /**
     * Get all classes methods in folder
     * @param $folder folder path
     * @param $depth folder search depth
     * @note Methods array cached in $_SESSION
     * @return array
     */
    public static function getAllMethods($folder, $depth)
    {
        if (($methods = Yii::$app->session->get('scheduler_methods', null)) === null) {
            $classes = static::getAllClasses($folder, $depth);

            $methods = [];
            foreach ($classes as $class) {
                $class_methods = get_class_methods($class);
                if ($parent_class = get_parent_class($class)) {
                    $parent_class_methods = get_class_methods($parent_class);
                    $class_methods = array_diff($class_methods, $parent_class_methods);
                }
                $methods[$class] = $class_methods;
            }
            Yii::$app->session->set('scheduler_methods', $methods);
        }

        return $methods;
    }

    /**
     * Get all classes
     * @param $folder folder path
     * @param $depth folder search depth
     * @return array
     */
    private static function getAllClasses($folder, $depth)
    {
        $finder = new Finder();
        $iter = new ClassIterator($finder->depth($depth)->files()->in($folder)); // too slow :(

        $classes = [];
        foreach ($iter->getClassMap() as $className => $splFileInfo) {
            $classes[] = $className;
        }

        return $classes;
    }
}