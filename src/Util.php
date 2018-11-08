<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 08-11-18
 * Time: 12:23
 */

namespace edwrodrig\reports;


use Traversable;

class Util
{

    /**
     * Split the traversable by criteria in an array of different elements
     *
     * ```
     * [1,2,3,4,5] => ['even' => [2,4], 'odd' => [1,3, 5]]
     * ```
     * An example of criteria function is the following:
     * ```
     * function (int $number) {
     *   if ( $number % 2 == 0 ) return 'even';
     *   else return 'odd'
     * }
     * ```
     *
     * Add function must receive a reference from the target. This function is useful when you want indexes
     * ```
     * function (array &target, $element) {
     *    $target[$element->getId()] = $element;
     * }
     * ```
     *
     *
     * @param Iterable $traversable If it is an object must have a trivial constructor
     * @param callable $criteria the criteria based in
     * @param callable|null $add_function
     * @return array
     */
    public static function split(Iterable $traversable, callable $criteria, ?callable $add_function = null) {
        $result = [];

        foreach ( $traversable as $element ) {

            $keys = $criteria($element);

            if ( is_string($keys) )
                $keys = [$keys];

            if ( count($keys) == 0 ) {
                $keys = ['without classification'];
            }

            foreach ( $keys as $key ) {
                if ( !isset($result[$key]) ) {

                    if ( is_array($traversable) ) {
                        $result[$key] = [];
                    } else if ( is_object($traversable) ) {
                        $class_name = get_class($traversable);
                        $result[$key] = new $class_name;
                    }
                }

                if ( is_null($add_function) ) {
                    $result[$key][] = $element;
                } else {
                    $add_function($result[$key], $element);
                }
            }
        }

        return $result;
    }
}