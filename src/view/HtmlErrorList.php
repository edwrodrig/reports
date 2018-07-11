<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 11-07-18
 * Time: 12:24
 */

namespace edwrodrig\reports\view;


use edwrodrig\reports\Report;
use edwrodrig\reports\ReportColumnError;

class HtmlErrorList
{
    /**
     * @var Report
     */
    private $report;

    /**
     * HtmlErrorList constructor.
     * @param Report $report
     */
    public function __construct(Report $report) {
        $this->report = $report;
    }

    /**
     * Print the report output.
     *
     * This creates a ready html table. This includes table, thead and tbody tags.
     * @uses HtmlTable::printHeaders()
     * @uses HtmlTable::printBody()
     */
    public function print() {?>
        <ol>
            <li>
            <?php $this->printHeaders()?>
            </li>
        </ol>
        <?php
    }

    /**
     * This print the table headers.
     *
     * Excludes the thead tag
     * @see https://www.w3schools.com/tags/tag_thead.asp thead
     */
    public function printErrors() {
        /* @var $error ReportColumnError */
        foreach ( $this->report->getErrors() as $error ) {
                $this->printError($error);
        }

    }

    public function printError(ReportColumnError $error)
    {
        printf(
                '<li><strong>%s@%s:</strong>%s</li>',
                $error->getColumnName(),
                $error->getId(),
                $error->getError()->getMessage()
        );
    }

    /**
     * Print a table row
     * @param $row
     */
    protected function printRow($row) {?>
        <tr>
            <?php foreach ( $row as $column_name => $column_value ) : ?>
                <?php if ( is_string($column_value)) : ?>
                    <td><?=$column_value?></td>
                <?php elseif ( is_numeric($column_value)) : ?>
                    <td><?=$column_value?></td>
                <?php elseif ( $column_value instanceof ReportColumnError ) :
                    $this->printErrorCell($column_value);
                else: ?>
                    <td></td>
                <?php endif; ?>
            <?php endforeach; ?>
        </tr>
        <?php
    }

    /**
     * Print an error row
     * @param ReportColumnError $error_column
     */
    protected function printErrorCell(ReportColumnError $error_column) {?>
        <td style="background-color:red"><?=$error_column->getError()->getMessage()?></td>
        <?php
    }
}