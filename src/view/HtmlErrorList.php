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

/**
 * Class HtmlErrorList
 *
 * Class to assist the creation of a erorrs list.
 * An error list is a way to display incomplete cells in a linear way.
 * It is easy to see a list than searching in a a table
 * @package edwrodrig\reports\view
 */
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
            <?php $this->printErrors()?>
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

    /**
     * Prints an error element in the error list
     *
     * Subclass this for different behaviors.
     * @param ReportColumnError $error
     */
    public function printError(ReportColumnError $error)
    {
        printf(
                '<li><strong>%s@%s:</strong>%s</li>',
                $error->getColumnName(),
                $error->getId(),
                $error->getError()->getMessage()
        );
    }
}