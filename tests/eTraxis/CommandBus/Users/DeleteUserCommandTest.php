<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2015 Artem Rodygin
//
//  This file is part of eTraxis.
//
//  You should have received a copy of the GNU General Public License
//  along with eTraxis.  If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------

namespace eTraxis\CommandBus\Users;

use eTraxis\Tests\BaseTestCase;

class DeleteUserCommandTest extends BaseTestCase
{
    public function testSuccess()
    {
        $this->loginAs('hubert');

        $user = $this->findUser('scruffy');
        $this->assertNotNull($user);

        $command = new DeleteUserCommand(['id' => $user->getId()]);
        $this->command_bus->handle($command);

        $user = $this->findUser('scruffy');
        $this->assertNull($user);
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function testForbidden()
    {
        $this->loginAs('fry');

        $user = $this->findUser('scruffy');
        $this->assertNotNull($user);

        $command = new DeleteUserCommand(['id' => $user->getId()]);
        $this->command_bus->handle($command);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testNotFound()
    {
        $this->loginAs('hubert');

        $command = new DeleteUserCommand(['id' => $this->getMaxId()]);
        $this->command_bus->handle($command);
    }
}