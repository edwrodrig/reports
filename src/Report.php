<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 05-07-18
 * Time: 15:54
 */

namespace edwrodrig\reports;

class Report
{
    protected $reader;

    protected $columns;

    public function __construct(ClassReader $reader) {
        $this->reader = $reader;

    }

    public function setColumns(array $columns) {
        $this->columns = $columns;
    }


}