<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 04-07-18
 * Time: 14:44
 */

namespace edwrodrig\reports;

use Error;
use Exception;
use edwrodrig\reports\exception\ColumnDoesNotExistException;
use edwrodrig\reports\exception\WrongInstanceException;
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
     * A rray holding the column objects
     * @var Column[]
     */
    private $columns = [];

    /**
     * ClassReader constructor.
     * @param string|object $class_or_object
     * @throws \ReflectionException
     */
    public function __construct($class_or_object) {
        $this->reflection_class = new ReflectionClass($class_or_object);
        $this->parse();
    }


    /**
     * Internal function that do the initial parsing of the object methods serachng for report columns
     * @throws exception\InvalidColumnFormat
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

    /**
     * @param $object
     * @param array $column_names
     * @return array
     * @throws ColumnDoesNotExistException
     * @throws WrongInstanceException
     */
    public function getValues($object, array $column_names = []) {

        if ( !$this->reflection_class->isInstance($object) )
            throw new WrongInstanceException($this->reflection_class->getName());

        if ( empty($column_names) ) {
            $column_names = $this->getColumnNames();
        }

        $values = [];

        foreach ( $column_names as $column_name ) {
            if ( !isset($this->columns[$column_name]) ) {
                throw new ColumnDoesNotExistException($this->reflection_class->getName(), $column_name);
            }

            $column = $this->columns[$column_name];
            try {
                $values[$column_name] = $column->getValue($object);
            } catch ( Exception | Error $e ) {
                $values[$column_name] = $e;
            }

        }
        return $values;

    }
}