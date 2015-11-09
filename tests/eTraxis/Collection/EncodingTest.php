<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2015 Artem Rodygin
//
//  You should have received a copy of the GNU General Public License
//  along with the file. If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------

namespace eTraxis\Collection;

use eTraxis\Tests\BaseTestCase;

class EncodingTest extends BaseTestCase
{
    public function testGetCollection()
    {
        $this->assertArrayHasKey('UTF-8', Encoding::getCollection());
    }
}
