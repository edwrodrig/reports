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
     * Get the number of rows
     *
     * @uses Report::getRows()
     * @return int
     */
    public function getNumRows() : int {
        return count($this->rows);
    }


    /**
     * Get the number of cells
     *
     * Must match the number of rows times the number of columns
     * @return int
     */
    public function getNumCells() : int  {
        return count($this->column_names) * $this->getNumRows();
    }

    /**
     * Get the number of cells that are valid
     *
     * An invalid cell is a cell that throws an error or exception
     * @return int
     */
    public function getNumValidCells() : int {
        return $this->getNumCells() - $this->getNumErrors();
    }

    /**
     * Get the completion ratio of the report
     *
     * the valid cells divided the total cells
     * @return float
     */
    public function getCompletitionRatio() : float {
        return $this->getNumValidCells() / $this->getNumCells();
    }

    /**
     * Get the number of errors
     *
     * The number of cells that throws errors
     * @return int
     */
    public function getNumErrors() : int {
        return count($this->getErrors());
    }

    /**
     * Get a list with errors
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