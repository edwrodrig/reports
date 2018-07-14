<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 14-07-18
 * Time: 11:12
 */

namespace edwrodrig\reports\view;


use edwrodrig\reports\Report;

class HtmlDefaultReport
{

    public function __construct(Report $report) {
        $this->report = $report;

    }

    public function printErrorSection() {
        $num_errors = count($this->report->getErrors());

        if ( $num_errors > 0 ) : ?>
            <h2>Errors (<?=$num_errors?>)</h2>
            <?php
            $error_list = new HtmlErrorList($this->report);
            $error_list->print();
        else : ?>
            <h2>No Errors</h2>
        <?php endif;
    }

    public function printCompletitionSection() {
        $percentage = (int)($this->report->getCompletitionRatio() * 100);
        ?>
        <div class="completition">
            <div class="completed" style="width:<?=$percentage?>%"></div>
            <div class="info"><?=sprintf(
                '%d/%d (%%%d)',
                $this->report->getNumValidCells(),
                $this->report->getNumCells(),
                $percentage)?></div>
        </div>
        <?php
    }

    public function print() {?>
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-size: 0.8em;
        }

        table {
            border-collapse: collapse;
        }

        th, td {
            padding: 0.5em;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        tr:nth-child(even) {background-color: #f2f2f2;}

        .completition {
            height: 2em;
            width: 100%;
            background-color: red;
            position: relative;
        }

        .completition > .completed {
            position: absolute;
            height: 100%;
            background-color: green;
        }

        .completition > .info {
            position: absolute;
            height: 100%;
            width: 100%;
            text-align: center;
        }
    </style>
</head>
<body>
<?php $this->printCompletitionSection()?>
<?php
        $table = new HtmlTable($this->report);
        $table->print();

        $this->printErrorSection();
?>
</body>
</html>
<?php
    }
}