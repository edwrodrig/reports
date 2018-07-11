<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 06-07-18
 * Time: 16:32
 */

namespace test\edwrodrig\reports;

use edwrodrig\reports\Report;
use PHPUnit\Framework\TestCase;
use test\edwrodrig\reports\dummy\RowObject;

class ReportTest extends TestCase
{

    /**
     * @throws \ReflectionException
     * @throws \edwrodrig\reports\exception\ColumnDoesNotExistException
     */
    public function testReportColumns() {
        $report = new Report(RowObject::class);

        $this->assertEquals(['data', 'number'], $report->getHeaders());

        $report->setColumnNames(['data']);
        $this->assertEquals(['data'], $report->getHeaders());
    }

    /**
     * @expectedException \edwrodrig\reports\exception\ColumnDoesNotExistException
     * @expectedExceptionMessage not_existant
     * @throws \ReflectionException
     * @throws \edwrodrig\reports\exception\ColumnDoesNotExistException
     */
    public function testReportInvalidColumns() {
        $report = new Report(RowObject::class);
        $report->setColumnNames(['not_existant']);
    }

}
