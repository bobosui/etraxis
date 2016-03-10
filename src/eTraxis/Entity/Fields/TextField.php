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
use eTraxis\Repository\TextValuesRepository;

/**
 * Text field.
 */
class TextField
{
    // Constraints.
    const MIN_LENGTH = 1;
    const MAX_LENGTH = 4000;

    // Properties.
    protected $field;
    protected $repository;

    /**
     * Constructor.
     *
     * @param   Field                $field
     * @param   TextValuesRepository $repository
     */
    public function __construct(Field $field, TextValuesRepository $repository)
    {
        $this->field      = $field;
        $this->repository = $repository;
    }

    /**
     * Sets maximum allowed length of field values.
     *
     * @param   int $length
     *
     * @return  self
     */
    public function setMaxLength($length)
    {
        if ($length < self::MIN_LENGTH) {
            $length = self::MIN_LENGTH;
        }

        if ($length > self::MAX_LENGTH) {
            $length = self::MAX_LENGTH;
        }

        $this->field->setParameter1($length);

        return $this;
    }

    /**
     * Returns maximum allowed length of field values.
     *
     * @return  int
     */
    public function getMaxLength()
    {
        return $this->field->getParameter1();
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
        if (mb_strlen($value) > self::MAX_LENGTH) {
            $value = substr($value, 0, self::MAX_LENGTH);
        }

        $id = ($value === null)
            ? null
            : $this->repository->save($value);

        $this->field->setDefaultValue($id);

        return $this;
    }

    /**
     * Returns default value of the field.
     *
     * @return  string|null
     */
    public function getDefaultValue()
    {
        $id = $this->field->getDefaultValue();

        if ($id === null) {
            return null;
        }

        /** @var \eTraxis\Entity\TextValue $value */
        $value = $this->repository->find($id);

        return $value->getValue();
    }
}