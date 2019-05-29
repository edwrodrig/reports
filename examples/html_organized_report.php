<?php
declare(strict_types=1);

use edwrodrig\reports\ReportSet;
use edwrodrig\reports\view\HtmlOrganizedReport;

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

$data = [
    'person_1' => [
        ["edwin", "rodríguez"],
        ["amanda", "morales"]
    ],
    'person_2' => [
        ["edgar", "rodríguez"],
        ["unknown", null]
    ]
];

$report = new ReportSet(Person::class, $data);

$html_report = new HtmlOrganizedReport($report);

$html_report->setTitle('Example report');
$html_report->setTargetDir('/home/edwin/persons');
$html_report->setIdColumnName('boss');
$html_report->setElementColumnName('persons');
$html_report->print();
