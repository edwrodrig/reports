<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 04-07-18
 * Time: 14:44
 */

namespace edwrodrig\reports;

use ReflectionClass;
use ReflectionMethod;

class ClassReader
{

    /**
     * Internal reflection class object
     * @var ReflectionClass
     */
    private $reflection_class;

    /**
     * Array holding the column objects
     * @var Column[]
     */
    private $columns = [];

    /**
     * ClassReader constructor.
     * @param string|object $class_or_object
     * @throws \ReflectionException
     * @throws exception\InvalidColumnFormatException
     */
    public function __construct($class_or_object) {
        $this->reflection_class = new ReflectionClass($class_or_object);
        $this->parse();
    }


    /**
     * Internal function that do the initial parsing of the object methods serachng for report columns
     * @throws exception\InvalidColumnFormatException
     */
    private function parse() {
        /**
         * @var $method ReflectionMethod
         */
        foreach ( $this->reflection_class->getMethods() as $method ) {
            if ( !$method->isPublic() ) continue;

            if ( !Column::isMethodReportColumn($method) ) continue;

            $column = new Column($method);
            $this->columns[$column->getName()] = $column;

        }
    }

    /**
     * Get all columns
     * @return Column[]
     */
    public function getColumns() : array {
        return $this->columns;
    }

    public function getClassName() : string {
        return $this->reflection_class->getName();
    }

    /**
     * Get the column names
     * @return string[]
     */
    public function getColumnNames() : array {
        return array_values(array_map(function(Column $column) { return $column->getName(); }, $this->columns));
    }

    /**
     * Get a particular column by name
     * @param string $column_name
     * @return Column
     */
    public function getColumn(string $column_name) {
        return $this->columns[$column_name];
    }

    public function getId() : string {
        return $this->getColumn('id');
    }
}