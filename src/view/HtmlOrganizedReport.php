<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 25-07-18
 * Time: 16:02
 */

namespace edwrodrig\reports\view;

use edwrodrig\reports\Report;
use edwrodrig\reports\ReportSet;

class HtmlOrganizedReport
{

    /**
     * @var ReportSet|Report[]
     */
    private $reports;

    /**
     * @var string
     */
    private $title = 'Report';

    private $element_column_name = 'Rows';

    private $id_column_name = 'Id';

    private $parent_url = null;

    private $target_dir = null;

    /**
     * HtmlDefaultReport constructor.
     * @param ReportSet $reports
     */
    public function __construct(ReportSet $reports)
    {
        $this->reports = $reports;
    }

    public function setTitle(string $title) : HtmlOrganizedReport {
        $this->title = $title;
        return $this;
    }

    public function setTargetDir(string $target_dir) : HtmlOrganizedReport{
        $this->target_dir = $target_dir;
        return $this;
    }

    public function setParentUrl(string $parent_url) : HtmlOrganizedReport {
        $this->parent_url = $parent_url;
        return $this;
    }

    public function setIdColumnName(string $id) : HtmlOrganizedReport {
        $this->id_column_name = $id;
        return $this;
    }

    public function setElementColumnName(string $element) : HtmlOrganizedReport {
        $this->element_column_name = $element;
        return $this;
    }

    /**
     *
     */
    public function printTable() { ?>
         <table>
            <thead>
            <tr>
                <th><?= $this->id_column_name ?></th>
                <th><?= $this->element_column_name ?></th>
                <th>Completed</th>
                <th>Errors</th>
                <th>Total</th>
                <th>Completion</th>
            </tr>
            </thead>
            <tbody>
        <?php
        /** @var Report $report */
        foreach ($this->reports as $report_name => $report) :
            $html_report = new HtmlDefaultReport($report);
            $html_report->setTitle($report_name);
            $html_report->setParentUrl('index.html');
            ob_start();
            $html_report->print();
            file_put_contents(sprintf('%s/%s.html', $this->target_dir, $report_name), ob_get_clean());

        ?>
            <tr>
                <td><a href="<?=$report_name?>.html"><?=$report_name?></a></td>
                <td><?=$report->getNumRows()?></td>
                <td><?=$report->getNumValidCells()?></td>
                <td><?=$report->getNumErrors()?></td>
                <td><?=$report->getNumCells()?></td>
                <td style="width:100%"><?php $html_report->printCompletitionSection()?></td>
            </tr>
        <?php endforeach;?>
            </tbody>
         </table>
        <?php
    }



    /**
     * Print the report
     */
    public function print() {

        if ( file_exists($this->target_dir) )
            system(sprintf("rm -rf %s", $this->target_dir));
        system(sprintf("mkdir -p %s", $this->target_dir));

        ob_start();
        ?>
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

                tr:nth-child(even) {
                    background-color: #f2f2f2;
                }

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
        <h1><?= $this->title?></h1>
         <?php $this->printTable()?>
        </body>
        </html>
        <?php

        file_put_contents(sprintf('%s/index.html', $this->target_dir), ob_get_clean());
    }
}