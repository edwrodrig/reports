<?php
declare(strict_types=1);

namespace edwrodrig\reports\exception;

use Exception;

class InvalidColumnFormatException extends Exception
{

    /**
     * InvalidColumnFormat constructor.
     * @param string $string
     */
    public function __construct(string $string)
    {
        parent::__construct($string);
    }
}