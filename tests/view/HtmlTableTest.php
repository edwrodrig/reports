<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 10-07-18
 * Time: 11:59
 */

namespace test\edwrodrig\reports\view;

use edwrodrig\reports\Report;
use edwrodrig\reports\view\HtmlTable;
use Exception;
use PHPUnit\Framework\TestCase;
use test\edwrodrig\reports\dummy\RowObject;

class HtmlTableTest extends TestCase
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


        $table = new HtmlTable($report);

        ob_start();
        $table->print();

        $expected = preg_replace('/\s+/', '', <<<EOF
        <table>
        <thead>
                <tr><th>data</th><th>number</th></tr>
        </thead>
        <tbody>
            <tr><td>edwin</td><td>1</td></tr>
            <tr><td>amanda</td><td>2</td></tr>
            <tr><td style="background-color:red">NO_NAME</td><td style="background-color:red">NO_NUMBER</td></tr>
        </tbody>
        </table>     
EOF
);

        $actual = preg_replace('/\s+/', '', ob_get_clean());
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


        $table = new HtmlTable($report);

        ob_start();
        $table->print();

        $expected = preg_replace('/\s+/', '', <<<EOF
        <table>
        <thead>
                <tr><th>data</th><th>number</th></tr>
        </thead>
        <tbody>
            <tr><td>edwin</td><td>1</td></tr>
            <tr><td><ul><li>amanda</li><li>morales</li></ul></td><td>2</td></tr>
            <tr><td style="background-color:red">NO_NAME</td><td style="background-color:red">NO_NUMBER</td></tr>
        </tbody>
        </table>     
EOF
        );

        $actual = preg_replace('/\s+/', '', ob_get_clean());
        $this->assertEquals($expected, $actual);
    }
}
