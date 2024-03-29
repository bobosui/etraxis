<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2014-2016 Artem Rodygin
//
//  You should have received a copy of the GNU General Public License
//  along with the file. If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------

namespace eTraxis\SimpleBus\States;

use eTraxis\Entity\State;
use eTraxis\Tests\BaseTestCase;

class UpdateStateCommandTest extends BaseTestCase
{
    public function testSuccess()
    {
        /** @var State $nextState */
        $nextState = $this->doctrine->getRepository(State::class)->findOneBy(['name' => 'New']);

        /** @var State $state */
        $state = $this->doctrine->getRepository(State::class)->findOneBy(['name' => 'Delivered']);

        self::assertNotNull($state);
        self::assertEquals('Delivered', $state->getName());
        self::assertEquals('D', $state->getAbbreviation());
        self::assertEquals(State::RESPONSIBLE_REMOVE, $state->getResponsible());
        self::assertNull($state->getNextState());

        $command = new UpdateStateCommand([
            'id'           => $state->getId(),
            'name'         => 'Completed',
            'abbreviation' => 'C',
            'responsible'  => State::RESPONSIBLE_KEEP,
            'nextState'    => $nextState->getId(),
        ]);

        $this->command_bus->handle($command);

        $state = $this->doctrine->getRepository(State::class)->findOneBy(['name' => 'Delivered']);

        self::assertNull($state);

        $state = $this->doctrine->getRepository(State::class)->findOneBy(['name' => 'Completed']);

        self::assertNotNull($state);
        self::assertEquals('Completed', $state->getName());
        self::assertEquals('C', $state->getAbbreviation());
        self::assertEquals(State::RESPONSIBLE_REMOVE, $state->getResponsible());
        self::assertEquals($state->getNextState()->getId(), $state->getNextState()->getId());
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @expectedExceptionMessage Unknown state.
     */
    public function testUnknownState()
    {
        $command = new UpdateStateCommand([
            'id'           => $this->getMaxId(),
            'name'         => 'Completed',
            'abbreviation' => 'C',
            'responsible'  => State::RESPONSIBLE_KEEP,
        ]);

        $this->command_bus->handle($command);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @expectedExceptionMessage Unknown next state.
     */
    public function testUnknownNextState()
    {
        /** @var State $state */
        $state = $this->doctrine->getRepository(State::class)->findOneBy(['name' => 'Delivered']);

        $command = new UpdateStateCommand([
            'id'           => $state->getId(),
            'name'         => 'Completed',
            'abbreviation' => 'C',
            'responsible'  => State::RESPONSIBLE_KEEP,
            'nextState'    => $this->getMaxId(),
        ]);

        $this->command_bus->handle($command);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage State with entered name already exists.
     */
    public function testNameConflict()
    {
        /** @var State $state */
        $state = $this->doctrine->getRepository(State::class)->findOneBy(['name' => 'Delivered']);

        $command = new UpdateStateCommand([
            'id'           => $state->getId(),
            'name'         => 'New',
            'abbreviation' => 'D',
            'responsible'  => State::RESPONSIBLE_REMOVE,
        ]);

        $this->command_bus->handle($command);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage State with entered abbreviation already exists.
     */
    public function testAbbreviationConflict()
    {
        /** @var State $state */
        $state = $this->doctrine->getRepository(State::class)->findOneBy(['name' => 'Delivered']);

        $command = new UpdateStateCommand([
            'id'           => $state->getId(),
            'name'         => 'Delivered',
            'abbreviation' => 'N',
            'responsible'  => State::RESPONSIBLE_REMOVE,
        ]);

        $this->command_bus->handle($command);
    }
}
