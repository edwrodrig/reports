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

    public function processErrorCell(ReportColumnError $error_column) {?>
        <td style="color:red"><?=$error_column->getError()->getMessage()?></td>
    <?php
    }

    public function print() { ?>
    <thead>
        <tr>
        <?php foreach ( $this->report->getHeaders() as $header ) :?>
            <th><?=$header?></th>
        <?php endforeach ?>
        </tr>
    </thead>
    <tbody>
    <?php foreach ( $this->report->getRows() as $row ) : ?>
        <tr>
        <?php foreach ( $row as $column_name => $column_value ) : ?>
            <?php if ( is_string($column_value)) : ?>
                <td><?=$column_value?></td>
            <?php elseif ( $column_value instanceof ReportColumnError ) :
                $this->processErrorCell($column_value);
            endif ?>
        <?php endforeach ?>
        </tr>
    <?php endforeach ?>
    </tbody>
    <?php
    }
}