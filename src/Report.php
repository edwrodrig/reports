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
     * @var Exception[]|Error[]
     */
    protected $errors = [];
    /**
     * Report constructor.
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
                $row[$column_name] = $this->processColumnValue($this->reader->getColumn($column_name)->getValue($object));
            } catch ( Exception | Error $e ) {
                $row[$column_name] = $this->processColumnError($e);
                $this->errors[] = new ReportColumnError($column_name, $object, $e);
            }
        }

        $this->rows[] = $row;
        return $row;

    }

    /**
     * Process the column value obtained from an object
     * @param $value
     * @return mixed
     */
    public function processColumnValue($value) {
        return $value;
    }

    /**
     * Process the column error obtained from a object.
     *
     * If a column throw an Exception then it is handled by this function
     * @param $e Exception|Error
     * @return string
     */
    public function processColumnError($e) : string {
       return sprintf("ERROR: %s [%s]", get_class($e), $e->getMessage());
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
        return $this->errors;
    }

}