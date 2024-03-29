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

class StateGroupTransitionTest extends \PHPUnit_Framework_TestCase
{
    /** @var StateGroupTransition */
    private $object;

    protected function setUp()
    {
        $this->object = new StateGroupTransition();
    }

    public function testFromStateId()
    {
        $expected = mt_rand(1, PHP_INT_MAX);
        $this->object->setFromStateId($expected);
        self::assertEquals($expected, $this->object->getFromStateId());
    }

    public function testToStateId()
    {
        $expected = mt_rand(1, PHP_INT_MAX);
        $this->object->setToStateId($expected);
        self::assertEquals($expected, $this->object->getToStateId());
    }

    public function testGroupId()
    {
        $expected = mt_rand(1, PHP_INT_MAX);
        $this->object->setGroupId($expected);
        self::assertEquals($expected, $this->object->getGroupId());
    }

    public function testFromState()
    {
        $this->object->setFromState($state = new State());
        self::assertSame($state, $this->object->getFromState());
    }

    public function testToState()
    {
        $this->object->setToState($state = new State());
        self::assertSame($state, $this->object->getToState());
    }

    public function testGroup()
    {
        $this->object->setGroup($group = new Group());
        self::assertSame($group, $this->object->getGroup());
    }
}
