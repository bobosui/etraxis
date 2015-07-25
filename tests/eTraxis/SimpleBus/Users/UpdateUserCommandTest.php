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

namespace eTraxis\SimpleBus\Users;

use eTraxis\Tests\BaseTestCase;

class UpdateUserCommandTest extends BaseTestCase
{
    public function testSuccess()
    {
        $this->loginAs('hubert');

        $user = $this->findUser('bender');

        $this->assertNotNull($user);
        $this->assertNotEmpty($user->getDescription());
        $this->assertFalse($user->isAdmin());
        $this->assertFalse($user->isDisabled());

        $command = new UpdateUserCommand([
            'id'       => $user->getId(),
            'username' => 'flexo',
            'fullname' => 'Flexo',
            'email'    => 'flexo@example.com',
            'admin'    => true,
            'disabled' => true,
        ]);

        $this->command_bus->handle($command);

        $user = $this->doctrine->getRepository('eTraxis:User')->find($user->getId());

        $this->assertEquals('flexo', $user->getUsername());
        $this->assertEquals('Flexo', $user->getFullname());
        $this->assertEquals('flexo@example.com', $user->getEmail());
        $this->assertEmpty($user->getDescription());
        $this->assertTrue($user->isAdmin());
        $this->assertTrue($user->isDisabled());
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testUnknownUser()
    {
        $this->loginAs('hubert');

        $user = $this->findUser('flexo');

        $this->assertNull($user);

        $command = new UpdateUserCommand([
            'id'       => $this->getMaxId(),
            'username' => 'flexo',
            'fullname' => 'Flexo',
            'email'    => 'flexo@example.com',
            'admin'    => true,
            'disabled' => true,
        ]);

        $this->command_bus->handle($command);
    }

    /**
     * @expectedException \eTraxis\SimpleBus\CommandException
     */
    public function testUsernameConflict()
    {
        $this->loginAs('hubert');

        $user = $this->findUser('fry');

        $this->assertNotNull($user);

        $command = new UpdateUserCommand([
            'id'       => $user->getId(),
            'username' => 'bender',
            'fullname' => $user->getFullname(),
            'email'    => $user->getEmail(),
            'admin'    => $user->isAdmin(),
            'disabled' => $user->isDisabled(),
        ]);

        $this->command_bus->handle($command);
    }
}