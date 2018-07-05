<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 05-07-18
 * Time: 12:32
 */

namespace edwrodrig\reports\exception;


use Exception;

class InvalidColumnFormat extends Exception
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