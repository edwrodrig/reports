<?php
declare(strict_types=1);

namespace edwrodrig\reports\exception;

use Exception;

class WrongInstanceException extends Exception
{

    /**
     * WrongInstanceException constructor.
     * @param string $class_name
     */
    public function __construct(string $class_name)
    {
            parent::__construct($class_name);
    }
}