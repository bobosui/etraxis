<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2014-2016 Artem Rodygin
//
//  You should have received a copy of the GNU General Public License
//  along with the file. If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------

namespace eTraxis\Tests;

class BaseTestCaseTest extends BaseTestCase
{
    public function testFindUser()
    {
        $this->assertNull($this->findUser('unknown'));

        $user = $this->findUser('artem');

        $this->assertInstanceOf('eTraxis\Entity\User', $user);
        $this->assertEquals('artem', $user->getUsername());
    }

    public function testGetMaxId()
    {
        $this->assertEquals(2147483647, $this->getMaxId());
    }

    public function testLoginAs()
    {
        $this->assertFalse($this->loginAs('unknown'));
        $this->assertTrue($this->loginAs('artem'));
        $this->assertTrue($this->loginAs('einstein', true));
    }
}
