<?php
declare(strict_types=1);

namespace test\edwrodrig\reports;

use edwrodrig\reports\ClassReader;
use Exception;
use PHPUnit\Framework\TestCase;
use test\edwrodrig\reports\dummy\RowObject;


class ClassReaderTest extends TestCase
{
    /**
     * @throws \ReflectionException
     * @throws \edwrodrig\reports\exception\WrongInstanceException
     * @throws \edwrodrig\reports\exception\InvalidColumnFormatException
     */
    public function testBase() {

        $object = new RowObject;
        $object->data = 'some_data';
        $object->number = 2;

        $reader = new ClassReader($object);
        $this->assertEquals(['data', 'number'], $reader->getColumnNames());

        $column = $reader->getColumn('data');
        $this->assertEquals('some_data', $column->getValue($object));

        $column = $reader->getColumn('number');
        $this->assertEquals(2, $column->getValue($object));
    }

    /**
     * @throws \ReflectionException
     * @throws \edwrodrig\reports\exception\WrongInstanceException
     * @expectedException \Exception
     * @expectedExceptionMessage hola
     */
    public function testGetValueException() {
        $object = new RowObject;
        $object->data = new Exception('hola');
        $object->number = 2;



        $reader = new ClassReader($object);

        $this->assertEquals(2, $reader->getColumn('number')->getValue($object));
        $reader->getColumn('data')->getValue($object);
    }

}
