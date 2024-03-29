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

class FilterTest extends \PHPUnit_Framework_TestCase
{
    /** @var Filter */
    private $object;

    protected function setUp()
    {
        $this->object = new Filter();
    }

    public function testId()
    {
        self::assertEquals(null, $this->object->getId());
    }

    public function testUserId()
    {
        $expected = mt_rand(1, PHP_INT_MAX);
        $this->object->setUserId($expected);
        self::assertEquals($expected, $this->object->getUserId());
    }

    public function testName()
    {
        $expected = 'Name';
        $this->object->setName($expected);
        self::assertEquals($expected, $this->object->getName());
    }

    public function testType()
    {
        $expected = Filter::TYPE_SEL_STATES;
        $this->object->setType($expected);
        self::assertEquals($expected, $this->object->getType());
    }

    public function testFlags()
    {
        $expected = Filter::FLAG_CREATED_BY | Filter::FLAG_ACTIVE;
        $this->object->setFlags($expected);
        self::assertEquals($expected, $this->object->getFlags());
    }

    public function testParameter()
    {
        $expected = mt_rand(1, PHP_INT_MAX);
        $this->object->setParameter($expected);
        self::assertEquals($expected, $this->object->getParameter());
    }

    public function testUser()
    {
        $this->object->setUser($user = new User());
        self::assertSame($user, $this->object->getUser());
    }
}
