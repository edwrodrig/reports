<?php
declare(strict_types=1);

namespace edwrodrig\reports;

use edwrodrig\reports\exception\ColumnDoesNotExistException;
use Error;
use Exception;

/**
 * Class Report
 * @package edwrodrig\reports
 */
class Report
{
    /**
     * The reader
     * @var ClassReader
     */
    protected $reader;

    /**
     * The selected columns
     * @var string[]
     */
    protected $column_names;

    protected $rows = [];

    /**
     * Report constructor.
     * @param $class_or_object
     * @throws \ReflectionException
     * @throws exception\InvalidColumnFormatException
     */
    public function __construct($class_or_object) {
        $this->reader = new ClassReader($class_or_object);

        $this->column_names = $this->reader->getColumnNames();
    }

    /**
     * @param string[] $column_names
     * @throws ColumnDoesNotExistException
     * @return $this
     */
    public function setColumnNames(array $column_names) : Report {
        $available_columns = $this->reader->getColumnNames();

        foreach ($column_names as $column_name ) {
            if ( !in_array($column_name, $available_columns) )
                throw new ColumnDoesNotExistException($this->reader->getClassName(), $column_name);
        }

        $this->column_names = $column_names;

        return $this;
    }

    /**
     * @param $object
     * @return array
     * @throws exception\WrongInstanceException
     */
    public function addRow($object) {

        $row = [];

        foreach ($this->column_names as $column_name ) {
            try {
                $row[$column_name] = $this->reader->getColumn($column_name)->getValue($object);
            } catch ( Exception | Error $e ) {
                $row[$column_name] = new ReportColumnError($column_name, $object, $e);
            }
        }

        $this->rows[] = $row;
        return $row;

    }

    /**
     * Just return the headers of this report
     * @return string[]
     */
    public function getHeaders() : array {
        return $this->column_names;
    }

    /**
     *
     * @return array
     */
    public function getRows() : array {
        return $this->rows;
    }

    /**
     * @return ReportColumnError[]
     */
    public function getErrors() : array {
        /* @var $errors ReportColumnError[] */
        $errors = [];

        foreach ( $this->rows as $row ) {
            foreach ( $row as $column ) {
                if ( $column instanceof ReportColumnError ) {
                    $errors[] = $column;
                }
            }
        }
        return $errors;
    }

}