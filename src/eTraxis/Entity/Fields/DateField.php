<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2014-2016 Artem Rodygin
//
//  You should have received a copy of the GNU General Public License
//  along with the file. If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------

namespace eTraxis\Entity\Fields;

use eTraxis\Entity\Field;

/**
 * Date field.
 */
class DateField extends AbstractField
{
    // Constraints.
    const MIN_VALUE = -2147483648;
    const MAX_VALUE = 2147483647;

    // Properties.
    protected $field;

    /**
     * Constructor.
     *
     * @param   Field $field
     */
    public function __construct(Field $field)
    {
        $this->field = $field;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSupportedKeys()
    {
        return ['minValue', 'maxValue', 'defaultValue'];
    }

    /**
     * Sets minimum allowed value of the field.
     *
     * @param   int $value
     *
     * @return  self
     */
    public function setMinValue($value)
    {
        if ($value < self::MIN_VALUE) {
            $value = self::MIN_VALUE;
        }

        if ($value > self::MAX_VALUE) {
            $value = self::MAX_VALUE;
        }

        $this->field->getParameters()->setParameter1($value);

        return $this;
    }

    /**
     * Returns minimum allowed value of the field.
     *
     * @return  int
     */
    public function getMinValue()
    {
        return $this->field->getParameters()->getParameter1();
    }

    /**
     * Sets maximum allowed value of the field.
     *
     * @param   string $value
     *
     * @return  self
     */
    public function setMaxValue($value)
    {
        if ($value < self::MIN_VALUE) {
            $value = self::MIN_VALUE;
        }

        if ($value > self::MAX_VALUE) {
            $value = self::MAX_VALUE;
        }

        $this->field->getParameters()->setParameter2($value);

        return $this;
    }

    /**
     * Returns maximum allowed value of the field.
     *
     * @return  string
     */
    public function getMaxValue()
    {
        return $this->field->getParameters()->getParameter2();
    }

    /**
     * Sets default value of the field.
     *
     * @param   string|null $value
     *
     * @return  self
     */
    public function setDefaultValue($value)
    {
        if ($value !== null) {

            if ($value < self::MIN_VALUE) {
                $value = self::MIN_VALUE;
            }

            if ($value > self::MAX_VALUE) {
                $value = self::MAX_VALUE;
            }
        }

        $this->field->getParameters()->setDefaultValue($value);

        return $this;
    }

    /**
     * Returns default value of the field.
     *
     * @return  string|null
     */
    public function getDefaultValue()
    {
        return $this->field->getParameters()->getDefaultValue();
    }
}
