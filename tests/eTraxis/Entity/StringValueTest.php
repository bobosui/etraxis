<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2014-2016 Artem Rodygin
//
//  You should have received a copy of the GNU General Public License
//  along with the file. If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------

namespace eTraxis\Entity;

class StringValueTest extends \PHPUnit_Framework_TestCase
{
    /** @var StringValue */
    private $object;

    protected function setUp()
    {
        $this->object = new StringValue();
    }

    public function testId()
    {
        self::assertEquals(null, $this->object->getId());
    }

    public function testToken()
    {
        $expected = 'token';
        $this->object->setToken($expected);
        self::assertEquals($expected, $this->object->getToken());
    }

    public function testValue()
    {
        $expected = str_pad('_', 150, '_');
        $this->object->setValue($expected);
        self::assertEquals($expected, $this->object->getValue());
    }
}
