<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 09-07-18
 * Time: 16:19
 */

namespace edwrodrig\reports\view;


use edwrodrig\reports\Report;
use edwrodrig\reports\ReportColumnError;

class HtmlTable
{
    /**
     * @var Report
     */
    private $report;


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
        <table>
        <thead>
        <?php $this->printHeaders()?>
        </thead>
        <tbody>
        <?php $this->printBody()?>
        </tbody>
        </table>
        <?php
    }

    /**
     * This print the table headers.
     *
     * Excludes the thead tag
     * @see https://www.w3schools.com/tags/tag_thead.asp thead
     */
    public function printHeaders() {?>
        <tr>
        <?php foreach ( $this->report->getHeaders() as $header ) :?>
            <th><?=$header?></th>
        <?php endforeach ?>
        </tr>
        <?php
    }

    /**
     * Print the table body rows
     *
     * This just loops and call {@see HtmlTable::printRow}. This excludes tbody
     * @see https://www.w3schools.com/tags/tag_tbody.asp tbody
     */
    public function printBody() {
        foreach ( $this->report->getRows() as $row )
            $this->printRow($row);
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
        <td style="color:red"><?=$error_column->getError()->getMessage()?></td>
        <?php
    }
}