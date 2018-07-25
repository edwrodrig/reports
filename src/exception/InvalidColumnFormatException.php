<?php
declare(strict_types=1);

namespace edwrodrig\reports\exception;

use Exception;

class InvalidColumnFormatException extends Exception
{

    /**
     * InvalidColumnFormatException constructor.
     * @param string $string
     */
    public function __construct(string $string)
    {
        parent::__construct($string);
    }
}