<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 04-07-18
 * Time: 15:58
 */

namespace edwrodrig\reports;

use BadMethodCallException;
use phpDocumentor\Reflection\DocBlockFactory;
use ReflectionClass;
use ReflectionMethod;

class Column
{
    /**
     * @var ReflectionMethod
     */
    private $reflection_method;

    /**
     * @var string
     */
    private $name;

    /**
     * Column constructor.
     * @param ReflectionClass $class
     * @param ReflectionMethod $method
     */
    public function __construct(ReflectionMethod $method) {
        $this->reflection_method = $method;

        $this->parse();

    }

    public function getName() : string {
        return $this->name;
    }

    /**
     * @throws exception\InvalidColumnFormat
     */
    private function parse() {
        $doc_comment = $this->reflection_method->getDocComment();

        $factory = DocBlockFactory::createInstance();

        $reader = $factory->create($doc_comment);
        {
            $tags = $reader->getTagsByName('report_column');
            if (empty($tags))
                throw new exception\InvalidColumnFormat('COLUMN_WITHOUT_NAME');

            $this->name = strval($tags[0]);
        }


    }

    /**
     * @param ReflectionMethod $method
     * @return bool
     */
    public static function isMethodReportColumn(ReflectionMethod $method) : bool {
        $doc_comment = $method->getDocComment();

        if ( $doc_comment === FALSE )  return false;

        $factory = DocBlockFactory::createInstance();
        $reader =  $factory->create($doc_comment);

        return $reader->hasTag('report_column');
    }

    /**
     * @param $object
     * @return mixed
     */
    public function getValue($object) {
        if ( is_null($object) ) {
            throw new BadMethodCallException("OBJECT UNDEFINED");
        }
        return $this->reflection_method->invoke($object);
    }

}