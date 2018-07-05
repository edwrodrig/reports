<?php
declare(strict_types=1);

namespace edwrodrig\reports\exception;

use Exception;

class ColumnDoesNotExistException extends Exception
{

    /**
     * ColumnDoesNotExistException constructor.
     * @param $class_name
     * @param mixed|string $column_name
     */
    public function __construct(string $class_name, string $column_name)
    {
        parent::__construct(sprintf("%s.%s", $class_name, $column_name));
    }
}