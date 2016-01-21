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

use eTraxis\Collection\Locale;
use eTraxis\Collection\Theme;
use eTraxis\Collection\Timezone;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Appearance form.
 */
class AppearanceForm extends AbstractType
{
    protected $translator;

    /**
     * Dependency Injection constructor.
     *
     * @param   TranslatorInterface $translator
     */
    public function __construct($translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Locale.
        $builder->add('locale', ChoiceType::class, [
            'label'             => 'language',
            'required'          => true,
            'choices'           => array_flip(Locale::getTranslatedCollection($this->translator)),
            'choices_as_values' => true,
        ]);

        // Theme.
        $builder->add('theme', ChoiceType::class, [
            'label'             => 'theme',
            'required'          => true,
            'choices'           => array_flip(Theme::getCollection()),
            'choices_as_values' => true,
        ]);

        // Timezone.
        $builder->add('timezone', ChoiceType::class, [
            'label'             => 'timezone',
            'required'          => true,
            'choices'           => array_flip(Timezone::getCollection()),
            'choices_as_values' => true,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appearance';
    }
}
