<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2014-2016 Artem Rodygin
//
//  You should have received a copy of the GNU General Public License
//  along with the file. If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------

namespace eTraxis\Traits;

class ClassAccessTraitTest extends \PHPUnit_Framework_TestCase
{
    public function testIsSet()
    {
        $object = new MyTestClassStub();

        self::assertTrue(isset($object->property));
        self::assertFalse(isset($object->unknown));
    }

    public function testGetPropertySuccess()
    {
        $object   = new MyTestClassStub();
        $expected = mt_rand();

        $object->setProperty($expected);

        self::assertEquals($expected, $object->property);
    }

    /**
     * @expectedException \Exception
     */
    public function testGetPropertyFailure()
    {
        $object = new MyTestClassStub();

        /** @noinspection PhpUndefinedFieldInspection */
        echo $object->unknown;
    }

    public function testSetPropertySuccess()
    {
        $object   = new MyTestClassStub();
        $expected = mt_rand();

        $object->property = $expected;

        self::assertEquals($expected, $object->getProperty());
    }

    /**
     * @expectedException \Exception
     */
    public function testSetPropertyFailure()
    {
        $object   = new MyTestClassStub();
        $expected = mt_rand();

        /** @noinspection PhpUndefinedFieldInspection */
        $object->unknown = $expected;
    }

    public function testCallMethodSuccess()
    {
        $object   = new MyTestClassStub();
        $expected = '<' . PHP_VERSION . '>';

        self::assertEquals($expected, $object->getVersion('<', '>'));
    }

    /**
     * @expectedException \Exception
     */
    public function testCallMethodFailure()
    {
        $object = new MyTestClassStub();

        /** @noinspection PhpUndefinedMethodInspection */
        $object->getUnknown();
    }
}
