<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 06-07-18
 * Time: 16:00
 */

namespace edwrodrig\reports;

/**
 * Class ReportColumnError
 *
 * A class to store important information of an column with an error
 * @package edwrodrig\reports
 */
class ReportColumnError
{
    /**
     * @var string
     */
    private $column_name;

    private $object;

    private $error;

    public function __construct(string $column_name, $object, $error) {
        $this->column_name = $column_name;
        $this->object = $object;
        $this->error = $error;
    }

    /**
     * Get the column name relative to the error
     * @return string
     */
    public function getColumnName() : string {
        return $this->column_name;
    }

    public function getId() : string {
        return $this->getObject()->getId();
    }

    /**
     * Get the object relative to the error
     * @return mixed
     */
    public function getObject() {
        return $this->object;
    }

    /**
     * Get the error itself, generally an throwable, error or exception
     * @return mixed
     */
    public function getError() {
        return $this->error;
    }
}