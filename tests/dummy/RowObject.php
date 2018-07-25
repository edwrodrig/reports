<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 06-07-18
 * Time: 16:36
 */

namespace test\edwrodrig\reports\dummy;


use Exception;

class RowObject
{

    public $data = [];

    public $number = 1;

    /**
     * @report_column data
     * @throws Exception
     */
    public function getData() {
        if ( $this->data instanceof Exception )
            throw $this->data;
        else
            return $this->data;
    }

    /**
     * @report_column number
     * @throws Exception
     */
    public function getNumber() {
        if ( $this->number instanceof Exception )
            throw $this->number;
        else
            return $this->number;
    }
}
