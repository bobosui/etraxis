<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2014-2016 Artem Rodygin
//
//  You should have received a copy of the GNU General Public License
//  along with the file. If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------

namespace eTraxis\Form;

use eTraxis\Collection\FieldType;
use eTraxis\Entity\Field;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Field form.
 */
class FieldForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Field $field */
        $field = $builder->getData();

        // Field name.
        $builder->add('name', TextType::class, [
            'label'    => 'field.name',
            'required' => true,
            'attr'     => ['maxlength' => Field::MAX_NAME],
        ]);

        // Field type.
        if (!is_object($field)) {
            $builder->add('type', ChoiceType::class, [
                'label'    => 'field.type',
                'required' => true,
                'choices'  => array_flip(FieldType::getCollection()),
                'data'     => Field::TYPE_STRING,
            ]);
        }

        // Type-specific inputs.
        if (!is_object($field) || $field->getType() === Field::TYPE_NUMBER) {
            $builder->add('asNumber', Fields\NumberFieldType::class);
        }
        if (!is_object($field) || $field->getType() === Field::TYPE_DECIMAL) {
            $builder->add('asDecimal', Fields\DecimalFieldType::class);
        }
        if (!is_object($field) || $field->getType() === Field::TYPE_STRING) {
            $builder->add('asString', Fields\StringFieldType::class);
        }
        if (!is_object($field) || $field->getType() === Field::TYPE_TEXT) {
            $builder->add('asText', Fields\TextFieldType::class);
        }
        if (!is_object($field) || $field->getType() === Field::TYPE_CHECKBOX) {
            $builder->add('asCheckbox', Fields\CheckboxFieldType::class);
        }
        if (!is_object($field) || $field->getType() === Field::TYPE_DATE) {
            $builder->add('asDate', Fields\DateFieldType::class);
        }
        if (!is_object($field) || $field->getType() === Field::TYPE_DURATION) {
            $builder->add('asDuration', Fields\DurationFieldType::class);
        }

        // Description.
        $builder->add('description', TextType::class, [
            'label'    => 'description',
            'required' => false,
            'attr'     => ['maxlength' => Field::MAX_DESCRIPTION],
        ]);

        // Required.
        if (!is_object($field) || $field->getType() !== Field::TYPE_CHECKBOX) {
            $builder->add('required', CheckboxType::class, [
                'label'    => 'field.required',
                'required' => false,
            ]);
        }

        // Guest access.
        $builder->add('guestAccess', CheckboxType::class, [
            'label'    => 'field.guest_access',
            'required' => false,
        ]);

        // Show in emails.
        $builder->add('showInEmails', CheckboxType::class, [
            'label'    => 'field.show_in_emails',
            'required' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'field';
    }
}
