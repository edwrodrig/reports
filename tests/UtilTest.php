<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 08-11-18
 * Time: 12:45
 */

namespace test\edwrodrig\reports;

use edwrodrig\reports\Util;
use PHPUnit\Framework\TestCase;

class UtilTest extends TestCase
{
    public function testArrayWithoutKeys() {
        $numbers = [1,2,3,4,5,6];

        $classified = Util::split($numbers, function(int $number) {
           if ( $number % 2 == 0 ) return 'even';
           else return 'odd';
        });

        $this->assertEquals(['odd' => [1,3, 5], 'even' => [2,4,6]], $classified);
    }

    public function testArrayWithKeys() {
        $numbers = [1,2,3,4,5,6];

        $classified = Util::split($numbers,
            function(int $number) {
                if ( $number % 2 == 0 ) return 'even';
                else return 'odd';
            },
            function(array &$target, int $number) {
                $target[$number] = $number;
            }
        );

        $this->assertEquals(['odd' => [1=>1,3=>3, 5=>5], 'even' => [2=>2,4=>4,6=>6]], $classified);
    }

    public function testClassWithoutKeys() {
        $numbers = new class implements \IteratorAggregate {
            public $elements = [];

            public function getIterator() {
                return new \ArrayIterator($this->elements);
            }

            public function addElement($element) {
                $this->elements[] = $element;
            }
        };

        $numbers->elements = [1,2,3,4,5,6];

        $classified = Util::split(
            $numbers,
            function(int $number) {
                if ( $number % 2 == 0 ) return 'even';
                else return 'odd';
            },
            function(&$target, $number) {
                $target->addElement($number);
            }
        );


        $this->assertEquals(['odd', 'even'], array_keys($classified));

        $this->assertInstanceOf(get_class($numbers), $classified['odd']);
        $this->assertInstanceOf(get_class($numbers), $classified['even']);


        $this->assertEquals([1,3,5], iterator_to_array($classified['odd']));
        $this->assertEquals([2,4,6], iterator_to_array($classified['even']));
    }
}
