<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 11-07-18
 * Time: 11:44
 */

use edwrodrig\reports\Report;
use edwrodrig\reports\view\HtmlErrorList;
use edwrodrig\reports\view\HtmlTable;

include_once __DIR__ . '/../vendor/autoload.php';

class Person
{
    public $name;
    public $surname;

    public function __construct($data)
    {
        $this->name = $data[0];
        $this->surname = $data[1];
    }

    /**
     * @report_column id
     */
    public function getId() : string { return $this->getName(); }

    /**
     * @report_column name
     */
    public function getName() : string { return $this->name; }

    /**
     * @report_column surname
     */
    public function getSurname() : string { return $this->surname; }
}

$report = new Report(Person::class);

$data = [
    ["edwin", "rodriguez"],
    ["amanda", "morales"],
    ["edgar", "rodriguez"],
    ["unknown", null]
];

foreach ( $data as $row )
    $report->addRow(new Person($row));

$table = new HtmlTable($report);

$table->print();


$error_list = new HtmlErrorList($report);
$error_list->print();
