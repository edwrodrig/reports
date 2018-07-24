<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 11-07-18
 * Time: 9:36
 */

namespace test\edwrodrig\reports\view;

use edwrodrig\reports\Report;
use edwrodrig\reports\view\CsvTable;
use Exception;
use PHPUnit\Framework\TestCase;
use test\edwrodrig\reports\dummy\RowObject;

class CsvTableTest extends TestCase
{

    public function testPrint()
    {
        $report = new Report(RowObject::class);

        $object = new RowObject;
        $object->data = 'edwin';
        $object->number = 1;

        $report->addRow($object);

        $object = new RowObject;
        $object->data = 'amanda';
        $object->number = 2;

        $report->addRow($object);

        $object = new RowObject;
        $object->data = new Exception('NO_NAME');
        $object->number = new Exception('NO_NUMBER');

        $report->addRow($object);


        $table = new CsvTable($report);


        $expected = <<<EOF
data,number
edwin,1
amanda,2
ERROR,ERROR

EOF;

        ob_start();
        $table->print();

        $actual = ob_get_clean();
        $this->assertEquals($expected, $actual);
    }


    public function testPrintArrayCell()
    {
        $report = new Report(RowObject::class);

        $object = new RowObject;
        $object->data = 'edwin';
        $object->number = 1;

        $report->addRow($object);

        $object = new RowObject;
        $object->data = ['amanda', 'morales'];
        $object->number = 2;

        $report->addRow($object);

        $object = new RowObject;
        $object->data = new Exception('NO_NAME');
        $object->number = new Exception('NO_NUMBER');

        $report->addRow($object);

        $table = new CsvTable($report);
        $table->setSeparator("\t");


        $expected = <<<EOF
data	number
edwin	1
amanda,morales	2
ERROR	ERROR

EOF;

        ob_start();
        $table->print();

        $actual = ob_get_clean();
        $this->assertEquals($expected, $actual);
    }
}
