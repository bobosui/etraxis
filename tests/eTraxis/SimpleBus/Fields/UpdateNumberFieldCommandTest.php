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

class UpdateNumberFieldCommandTest extends BaseTestCase
{
    public function testSuccess()
    {
        /** @var Field $field */
        $field = $this->doctrine->getRepository(Field::class)->findOneBy(['name' => 'Episode']);

        self::assertEquals(Field::TYPE_NUMBER, $field->getType());
        self::assertEquals('Episode', $field->getName());
        self::assertNull($field->getDescription());
        self::assertTrue($field->isRequired());
        self::assertTrue($field->hasGuestAccess());
        self::assertFalse($field->getShowInEmails());
        self::assertEquals(1, $field->getParameters()->getParameter1());
        self::assertEquals(100, $field->getParameters()->getParameter2());
        self::assertNull($field->getParameters()->getDefaultValue());

        $command = new UpdateNumberFieldCommand([
            'id'           => $field->getId(),
            'name'         => 'Episode #',
            'description'  => 'ID of the episode',
            'required'     => false,
            'guestAccess'  => false,
            'showInEmails' => true,
            'minValue'     => 0,
            'maxValue'     => 50,
            'defaultValue' => 1,
        ]);

        $this->command_bus->handle($command);

        $field = $this->doctrine->getRepository(Field::class)->find($field->getId());

        self::assertEquals(Field::TYPE_NUMBER, $field->getType());
        self::assertEquals('Episode #', $field->getName());
        self::assertEquals('ID of the episode', $field->getDescription());
        self::assertFalse($field->isRequired());
        self::assertFalse($field->hasGuestAccess());
        self::assertTrue($field->getShowInEmails());
        self::assertEquals(0, $field->getParameters()->getParameter1());
        self::assertEquals(50, $field->getParameters()->getParameter2());
        self::assertEquals(1, $field->getParameters()->getDefaultValue());
    }

    /**
     * @expectedException \SimpleBus\ValidationException
     * @expectedExceptionMessage Maximum value should be greater then minimum one.
     */
    public function testMinMaxValues()
    {
        /** @var Field $field */
        $field = $this->doctrine->getRepository(Field::class)->findOneBy(['name' => 'Episode']);

        self::assertNotNull($field);

        $command = new UpdateNumberFieldCommand([
            'id'           => $field->getId(),
            'name'         => $field->getName(),
            'required'     => $field->isRequired(),
            'guestAccess'  => $field->hasGuestAccess(),
            'showInEmails' => $field->getShowInEmails(),
            'minValue'     => 100,
            'maxValue'     => 1,
        ]);

        $this->command_bus->handle($command);
    }

    /**
     * @expectedException \SimpleBus\ValidationException
     * @expectedExceptionMessage Default value should be in range from 1 to 100.
     */
    public function testDefaultValue()
    {
        /** @var Field $field */
        $field = $this->doctrine->getRepository(Field::class)->findOneBy(['name' => 'Episode']);

        self::assertNotNull($field);

        $command = new UpdateNumberFieldCommand([
            'id'           => $field->getId(),
            'name'         => $field->getName(),
            'required'     => $field->isRequired(),
            'guestAccess'  => $field->hasGuestAccess(),
            'showInEmails' => $field->getShowInEmails(),
            'minValue'     => 1,
            'maxValue'     => 100,
            'defaultValue' => 0,
        ]);

        $this->command_bus->handle($command);
    }
}
