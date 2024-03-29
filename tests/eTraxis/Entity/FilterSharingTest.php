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

class FilterSharingTest extends \PHPUnit_Framework_TestCase
{
    /** @var FilterSharing */
    private $object;

    protected function setUp()
    {
        $this->object = new FilterSharing();
    }

    public function testFilterId()
    {
        $expected = mt_rand(1, PHP_INT_MAX);
        $this->object->setFilterId($expected);
        self::assertEquals($expected, $this->object->getFilterId());
    }

    public function testGroupId()
    {
        $expected = mt_rand(1, PHP_INT_MAX);
        $this->object->setGroupId($expected);
        self::assertEquals($expected, $this->object->getGroupId());
    }

    public function testFilter()
    {
        $this->object->setFilter($filter = new Filter());
        self::assertSame($filter, $this->object->getFilter());
    }

    public function testGroup()
    {
        $this->object->setGroup($group = new Group());
        self::assertSame($group, $this->object->getGroup());
    }
}
