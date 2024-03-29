<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2014-2016 Artem Rodygin
//
//  You should have received a copy of the GNU General Public License
//  along with the file. If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------

namespace eTraxis\SimpleBus\Users;

use eTraxis\Entity\Group;
use eTraxis\Entity\User;
use eTraxis\Tests\BaseTestCase;

class RemoveGroupsCommandTest extends BaseTestCase
{
    public function testSuccess()
    {
        /** @var \eTraxis\Repository\UsersRepository $repository */
        $repository = $this->doctrine->getManager()->getRepository(User::class);

        $user = $this->findUser('hubert');

        $groups = $repository->getUserGroups($user->getId());
        $others = $repository->getOtherGroups($user->getId());

        self::assertNotCount(0, $groups);
        self::assertNotCount(0, $others);

        $expected = count($groups) + count($others);

        $command = new RemoveGroupsCommand([
            'id'     => $user->getId(),
            'groups' => array_map(function (Group $group) {
                return $group->getId();
            }, $groups),
        ]);

        $this->command_bus->handle($command);

        $groups = $repository->getUserGroups($user->getId());
        $others = $repository->getOtherGroups($user->getId());

        self::assertCount(0, $groups);
        self::assertCount($expected, $others);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @expectedExceptionMessage Unknown user.
     */
    public function testNotFound()
    {
        $command = new RemoveGroupsCommand([
            'id'     => $this->getMaxId(),
            'groups' => [1, 2, 3],
        ]);

        $this->command_bus->handle($command);
    }

    /**
     * @expectedException \SimpleBus\ValidationException
     * @expectedExceptionMessage This collection should contain 1 element or more.
     */
    public function testEmptyGroups()
    {
        $user = $this->findUser('hubert');

        $command = new RemoveGroupsCommand([
            'id'     => $user->getId(),
            'groups' => [],
        ]);

        $this->command_bus->handle($command);
    }
}
