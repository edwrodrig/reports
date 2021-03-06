<?php
declare(strict_types=1);

use edwrodrig\reports\Report;
use edwrodrig\reports\view\HtmlDefaultReport;

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
    ["edwin", "rodríguez"],
    ["amanda", "morales"],
    ["edgar", "rodríguez"],
    ["unknown", null]
];

$report = Report::createFromArray(Person::class, $data);

$html_report = new HtmlDefaultReport($report);
$html_report
        ->setTitle('Example report')
        ->setParentUrl('http://www.google.cl')
        ->print();
