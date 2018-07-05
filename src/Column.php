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
     * @param ReflectionMethod $method
     * @throws exception\InvalidColumnFormat
     */
    public function __construct(ReflectionMethod $method) {
        $this->reflection_method = $method;

        $this->parse();

    }

    /**
     * Get the name of the current column
     * @return string
     */
    public function getName() : string {
        return $this->name;
    }

    /**
     * This function parses the method relative to the column
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
     * Check if the method is a report column
     *
     * If the method as an @report_column annotation in the doc comment
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
     * Get the value of the column.
     *
     * This function calls the method relative to the column. You must pass the final object.
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