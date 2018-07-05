<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 05-07-18
 * Time: 12:03
 */

namespace test\edwrodrig\reports;

use edwrodrig\reports\ClassReader;
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

}
