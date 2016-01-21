<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2014-2016 Artem Rodygin
//
//  You should have received a copy of the GNU General Public License
//  along with the file. If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------

namespace eTraxis\Repository;

use eTraxis\Tests\BaseTestCase;

class TextValuesRepositoryTest extends BaseTestCase
{
    public function testNewValue()
    {
        $expected = 'Artem Rodygin';

        /** @var TextValuesRepository $repository */
        $repository = $this->doctrine->getManager()->getRepository('eTraxis:TextValue');

        $count = count($repository->findAll());

        /** @var \eTraxis\Entity\TextValue $value */
        $value = $repository->findOneBy(['value' => $expected]);

        $this->assertNull($value);

        // First attempt.
        $id1 = $repository->save($expected);

        $value = $repository->findOneBy(['value' => $expected]);

        $this->assertNotNull($value);
        $this->assertEquals($id1, $value->getId());
        $this->assertEquals($expected, $value->getValue());
        $this->assertCount($count + 1, $repository->findAll());

        // Second attempt.
        $id2 = $repository->save($expected);

        $this->assertEquals($id1, $id2);
        $this->assertCount($count + 1, $repository->findAll());
    }
}
