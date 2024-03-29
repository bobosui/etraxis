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

use eTraxis\Tests\BaseTestCase;

class FieldTest extends BaseTestCase
{
    /** @var Field */
    private $object;

    protected function setUp()
    {
        parent::setUp();

        $this->object = new Field();

        /** @noinspection PhpParamsInspection */
        $this->object
            ->setDecimalValuesRepository($this->doctrine->getRepository(DecimalValue::class))
            ->setStringValuesRepository($this->doctrine->getRepository(StringValue::class))
            ->setTextValuesRepository($this->doctrine->getRepository(TextValue::class))
            ->setListItemsRepository($this->doctrine->getRepository(ListItem::class))
        ;
    }

    public function testId()
    {
        self::assertEquals(null, $this->object->getId());
    }

    public function testTemplateId()
    {
        $expected = mt_rand(1, PHP_INT_MAX);
        $this->object->setTemplateId($expected);
        self::assertEquals($expected, $this->object->getTemplateId());
    }

    public function testStateId()
    {
        $expected = mt_rand(1, PHP_INT_MAX);
        $this->object->setStateId($expected);
        self::assertEquals($expected, $this->object->getStateId());
    }

    public function testName()
    {
        $expected = 'Name';
        $this->object->setName($expected);
        self::assertEquals($expected, $this->object->getName());
    }

    public function testType()
    {
        $this->object->setType(Field::TYPE_NUMBER);
        self::assertEquals('number', $this->object->getType());

        $this->object->setType(Field::TYPE_DECIMAL);
        self::assertEquals('decimal', $this->object->getType());

        $this->object->setType(Field::TYPE_STRING);
        self::assertEquals('string', $this->object->getType());

        $this->object->setType(Field::TYPE_TEXT);
        self::assertEquals('text', $this->object->getType());

        $this->object->setType(Field::TYPE_CHECKBOX);
        self::assertEquals('checkbox', $this->object->getType());

        $this->object->setType(Field::TYPE_LIST);
        self::assertEquals('list', $this->object->getType());

        $this->object->setType(Field::TYPE_RECORD);
        self::assertEquals('record', $this->object->getType());

        $this->object->setType(Field::TYPE_DATE);
        self::assertEquals('date', $this->object->getType());

        $this->object->setType(Field::TYPE_DURATION);
        self::assertEquals('duration', $this->object->getType());

        $this->object->setType('unknown');
        self::assertEquals('duration', $this->object->getType());
    }

    public function testDescription()
    {
        $expected = 'Description';
        $this->object->setDescription($expected);
        self::assertEquals($expected, $this->object->getDescription());
    }

    public function testIndexNumber()
    {
        $expected = mt_rand(1, PHP_INT_MAX);
        $this->object->setIndexNumber($expected);
        self::assertEquals($expected, $this->object->getIndexNumber());
    }

    public function testRemovedAt()
    {
        $expected = time();
        $this->object->setRemovedAt($expected);
        self::assertEquals($expected, $this->object->getRemovedAt());
    }

    public function testIsRequired()
    {
        $this->object->setRequired(false);
        self::assertFalse($this->object->isRequired());

        $this->object->setRequired(true);
        self::assertTrue($this->object->isRequired());
    }

    public function testHasGuestAccess()
    {
        $this->object->setGuestAccess(false);
        self::assertFalse($this->object->hasGuestAccess());

        $this->object->setGuestAccess(true);
        self::assertTrue($this->object->hasGuestAccess());
    }

    public function testRegisteredAccess()
    {
        $expected = Field::ACCESS_READ_ONLY;

        $this->object->setRegisteredAccess($expected);
        self::assertEquals($expected, $this->object->getRegisteredAccess());
    }

    public function testAuthorAccess()
    {
        $expected = Field::ACCESS_READ_ONLY;

        $this->object->setAuthorAccess($expected);
        self::assertEquals($expected, $this->object->getAuthorAccess());
    }

    public function testResponsibleAccess()
    {
        $expected = Field::ACCESS_READ_ONLY;

        $this->object->setResponsibleAccess($expected);
        self::assertEquals($expected, $this->object->getResponsibleAccess());
    }

    public function testShowInEmails()
    {
        $this->object->setShowInEmails(false);
        self::assertFalse($this->object->getShowInEmails());

        $this->object->setShowInEmails(true);
        self::assertTrue($this->object->getShowInEmails());
    }

    public function testRegex()
    {
        self::assertNotNull($this->object->getRegex());
        self::assertInstanceOf(FieldRegex::class, $this->object->getRegex());
    }

    public function testParameters()
    {
        self::assertNotNull($this->object->getParameters());
        self::assertInstanceOf(FieldParameters::class, $this->object->getParameters());
    }

    public function testTemplate()
    {
        $this->object->setTemplate($template = new Template());
        self::assertSame($template, $this->object->getTemplate());
    }

    public function testState()
    {
        $this->object->setState($state = new State());
        self::assertSame($state, $this->object->getState());
    }

    public function testFacades()
    {
        self::assertInstanceOf(Fields\NumberField::class,   $this->object->asNumber());
        self::assertInstanceOf(Fields\DecimalField::class,  $this->object->asDecimal());
        self::assertInstanceOf(Fields\StringField::class,   $this->object->asString());
        self::assertInstanceOf(Fields\TextField::class,     $this->object->asText());
        self::assertInstanceOf(Fields\CheckboxField::class, $this->object->asCheckbox());
        self::assertInstanceOf(Fields\ListField::class,     $this->object->asList());
        self::assertInstanceOf(Fields\RecordField::class,   $this->object->asRecord());
        self::assertInstanceOf(Fields\DateField::class,     $this->object->asDate());
        self::assertInstanceOf(Fields\DurationField::class, $this->object->asDuration());
    }
}
