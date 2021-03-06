<?php
declare(strict_types=1);

namespace edwrodrig\reports\view;

use edwrodrig\reports\Report;
use edwrodrig\reports\ReportColumnError;

/**
 * Class CsvTable
 *
 * This class helps to display a {@see Report report} as a CsvTable
 * @package edwrodrig\reports\view
 */
class CsvTable
{
    /**
     * @var Report
     */
    private $report;


    private $separator = ',';

    /**
     * CsvTable constructor.
     * @param Report $report
     */
    public function __construct(Report $report) {
        $this->report = $report;
    }

    /**
     * Set the separator of the table
     * @param string $separator
     */
    public function setSeparator(string $separator) {
        $this->separator = $separator;
    }

    /**
     * Print the report output.
     *
     * This creates a ready html table. This includes table, thead and tbody tags.
     * @uses HtmlTable::printHeaders()
     * @uses HtmlTable::printBody()
     */
    public function print() {
        $this->printHeaders();
        $this->printBody();
    }

    /**
     * This print the table headers.
     *
     * Excludes the thead tag
     * @see https://www.w3schools.com/tags/tag_thead.asp thead
     */
    public function printHeaders() {
        echo implode($this->separator, $this->report->getHeaders());
        echo "\n";
    }

    /**
     * Print the table body rows
     *
     * This just loops and call {@see HtmlTable::printRow}. This excludes tbody
     * @see https://www.w3schools.com/tags/tag_tbody.asp tbody
     */
    public function printBody() {

        foreach ( $this->report->getRows() as $row ) {
            echo $this->getRow($row);
            echo "\n";
        }
    }

    /**
     * Get a table row
     *
     * Get a table row suitable for printing
     * @param $row
     * @return string
     */
    protected function getRow($row) : string{
        $row_values = [];
        foreach ( $row as $column_name => $column_value ) {
            if ( is_array($column_value))
                $row_values[] = implode(',', $column_value);
            elseif (is_string($column_value))
                $row_values[] = $column_value;
            elseif (is_numeric($column_value))
                $row_values[] = $column_value;
            elseif ($column_value instanceof ReportColumnError)
                $row_values[] = $this->getErrorCell($column_value);
            else
                $row_values[] = '';
        }

        return implode($this->separator, $row_values);
    }

    /**
     * Get an error row
     *
     * Build an error string from a error column
     * @param ReportColumnError $error_column
     * @return string
     */
    protected function getErrorCell(ReportColumnError $error_column) : string{
        return "ERROR";
    }
}