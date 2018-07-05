<?php
declare(strict_types=1);

namespace test\edwrodrig\reports;

use edwrodrig\reports\ClassReader;
use Exception;
use PHPUnit\Framework\TestCase;


class ClassReaderTest extends TestCase
{
    /**
     * @throws \ReflectionException
     */
    public function testBase() {

        $object = new class {
            /**
             * @report_column data
             */
            public function getData() {
                return 'some_data';
            }

            /**
             * @report_column number
             */
            public function getNumber() {
                return 2;
            }
        };

        $reader = new ClassReader($object);
        $this->assertEquals(['data', 'number'], $reader->getColumnNames());

        $column = $reader->getColumn('data');
        $this->assertEquals('some_data', $column->getValue($object));

        $column = $reader->getColumn('number');
        $this->assertEquals(2, $column->getValue($object));
    }


    /**
     * @throws \ReflectionException
     * @throws \edwrodrig\reports\exception\ColumnDoesNotExistException
     * @throws \edwrodrig\reports\exception\WrongInstanceException
     */
    public function testGetValues() {
        $object = new class {
            /**
             * @report_column data
             */
            public function getData() {
                return 'some_data';
            }

            /**
             * @report_column number
             */
            public function getNumber() {
                return 2;
            }
        };

        $reader = new ClassReader($object);

        $this->assertEquals(['data' => 'some_data', 'number' => 2], $reader->getValues($object, ['data', 'number']));
        $this->assertEquals(['data' => 'some_data', 'number' => 2], $reader->getValues($object));
        $this->assertEquals(['data' => 'some_data'], $reader->getValues($object, ['data']));
        $this->assertEquals(['number' => 2], $reader->getValues($object, ['number']));
        $this->assertEquals(['number' => 2, 'data' => 'some_data'], $reader->getValues($object, ['number', 'data']));
    }

    /**
     * @throws \ReflectionException
     * @throws \edwrodrig\reports\exception\ColumnDoesNotExistException
     * @throws \edwrodrig\reports\exception\WrongInstanceException
     */
    public function testGetValueException() {
        $object = new class {
            /**
             * @report_column data
             */
            public function getData() {
                throw new Exception('hola');
            }

            /**
             * @report_column number
             */
            public function getNumber() {
                return 2;
            }
        };

        $reader = new ClassReader($object);

        $values = $reader->getValues($object);
        $this->assertEquals(2, $values['number']);
        $this->assertInstanceOf(Exception::class, $values['data']);
    }

}
