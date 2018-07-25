<?php
declare(strict_types=1);

namespace edwrodrig\reports\view;

use edwrodrig\reports\Report;

class HtmlDefaultReport
{

    /**
     * @var Report
     */
    private $report;

    /**
     * @var string
     */
    private $title = 'Report';


    private $parent_url = null;

    /**
     * HtmlDefaultReport constructor.
     * @param Report $report
     */
    public function __construct(Report $report) {
        $this->report = $report;

    }

    public function setTitle(string $title) {
        $this->title = $title;
    }

    public function setParentUrl(string $parent_url) {
        $this->parent_url = $parent_url;
    }

    /**
     * Print the tabular section of the report.
     *
     * @uses HtmlTable
     */
    public function printTableSection() {
        $table = new HtmlTable($this->report);
        $table->print();
    }

    /**
     * Prints the error section.
     *
     * The list of errors.
     * @uses HtmlErrorList
     */
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

    /**
     * Prints the completition bar of all the report data.
     *
     * Very handy to see the global state of the data.
     */
    public function printCompletitionSection() {
        $percentage = (int)($this->report->getCompletitionRatio() * 100);
        ?>
        <div class="completition">
            <div class="completed" style="width:<?=$percentage?>%"></div>
            <div class="info"><?=sprintf(
                '%d/%d (%d%%)',
                $this->report->getNumValidCells(),
                $this->report->getNumCells(),
                $percentage)?></div>
        </div>
        <?php
    }

    /**
     * Print the report
     */
    public function print() {?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
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
<h1><?=$this->title?></h1>
<?php if ( !is_null($this->parent_url) ) : ?>
    <a href="<?=$this->parent_url?>">Back to parent</a>
<?php endif;
    $this->printCompletitionSection();
    $this->printTableSection();
    $this->printErrorSection();
?>
</body>
</html>
<?php
    }
}