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

class FilterStateTest extends \PHPUnit_Framework_TestCase
{
    /** @var FilterState */
    private $object;

    protected function setUp()
    {
        $this->object = new FilterState();
    }

    public function testFilterId()
    {
        $expected = mt_rand(1, PHP_INT_MAX);
        $this->object->setFilterId($expected);
        self::assertEquals($expected, $this->object->getFilterId());
    }

    public function testStateId()
    {
        $expected = mt_rand(1, PHP_INT_MAX);
        $this->object->setStateId($expected);
        self::assertEquals($expected, $this->object->getStateId());
    }

    public function testFilter()
    {
        $this->object->setFilter($filter = new Filter());
        self::assertSame($filter, $this->object->getFilter());
    }

    public function testState()
    {
        $this->object->setState($state = new State());
        self::assertSame($state, $this->object->getState());
    }
}
