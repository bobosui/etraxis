<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2014-2016 Artem Rodygin
//
//  You should have received a copy of the GNU General Public License
//  along with the file. If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------

namespace eTraxis\SimpleBus\Fields;

use eTraxis\Entity\Field;
use eTraxis\Tests\BaseTestCase;

class CreateDateFieldCommandTest extends BaseTestCase
{
    public function testSuccess()
    {
        /** @var \eTraxis\Entity\State $state */
        $state = $this->doctrine->getRepository('eTraxis:State')->findOneBy(['name' => 'New']);

        $this->assertNotNull($state);

        $command = new CreateDateFieldCommand([
            'template'     => $state->getTemplateId(),
            'state'        => $state->getId(),
            'name'         => 'Deadline',
            'required'     => true,
            'guestAccess'  => false,
            'showInEmails' => false,
            'minValue'     => 1,
            'maxValue'     => 7,
            'default'      => 2,
        ]);

        $this->command_bus->handle($command);

        /** @var Field $field */
        $field = $this->doctrine->getRepository('eTraxis:Field')->findOneBy(['name' => $command->name]);

        $this->assertInstanceOf('\eTraxis\Entity\Field', $field);
        $this->assertEquals(Field::TYPE_DATE, $field->getType());
        $this->assertEquals(1, $field->getParameter1());
        $this->assertEquals(7, $field->getParameter2());
        $this->assertEquals(2, $field->getDefaultValue());
    }

    /**
     * @expectedException \eTraxis\SimpleBus\Middleware\ValidationException
     * @expectedExceptionMessage Maximum value should be greater then minimum one.
     */
    public function testMinMaxValues()
    {
        /** @var \eTraxis\Entity\State $state */
        $state = $this->doctrine->getRepository('eTraxis:State')->findOneBy(['name' => 'New']);

        $this->assertNotNull($state);

        $command = new CreateDateFieldCommand([
            'template'     => $state->getTemplateId(),
            'state'        => $state->getId(),
            'name'         => 'Deadline',
            'required'     => true,
            'guestAccess'  => false,
            'showInEmails' => false,
            'minValue'     => 7,
            'maxValue'     => 1,
        ]);

        $this->command_bus->handle($command);
    }

    /**
     * @expectedException \eTraxis\SimpleBus\Middleware\ValidationException
     * @expectedExceptionMessage Default value should be in range from 1 to 7.
     */
    public function testDefaultValue()
    {
        /** @var \eTraxis\Entity\State $state */
        $state = $this->doctrine->getRepository('eTraxis:State')->findOneBy(['name' => 'New']);

        $this->assertNotNull($state);

        $command = new CreateDateFieldCommand([
            'template'     => $state->getTemplateId(),
            'state'        => $state->getId(),
            'name'         => 'Deadline',
            'required'     => true,
            'guestAccess'  => false,
            'showInEmails' => false,
            'minValue'     => 1,
            'maxValue'     => 7,
            'default'      => 10,
        ]);

        $this->command_bus->handle($command);
    }
}
