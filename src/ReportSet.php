<?php
declare(strict_types=1);


namespace edwrodrig\reports;

use ArrayIterator;
use IteratorAggregate;

class ReportSet implements IteratorAggregate
{
    /**
     * @var Report[]
     */
    private $reports;

    /**
     * Create a report from array
     *
     * It just call the constructor an then call {@see Report::addRow()} for each element in the array.
     * @param $class_or_object
     * @param array $data
     * @throws \ReflectionException
     * @throws \edwrodrig\reports\exception\InvalidColumnFormatException
     */
    public function __construct($class_or_object, $data) {
        foreach ( $data as $key => $value ) {
            $this->reports[$key] = Report::createFromArray($class_or_object, $value);
        }

        uasort($this->reports, function(Report $a, Report $b) { return $b->getCompletitionRatio() <=> $a->getCompletitionRatio(); });
    }

    /**
     * @return ArrayIterator|\Traversable|Report[]
     */
    public function getIterator() {
        return new ArrayIterator($this->reports);
    }
}