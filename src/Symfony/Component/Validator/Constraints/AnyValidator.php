<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2014-2016 Artem Rodygin
//
//  You should have received a copy of the GNU General Public License
//  along with the file. If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------

namespace Symfony\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * A validator for "Any" constraint.
 */
class AnyValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        /** @var \Symfony\Component\Validator\Context\ExecutionContextInterface $context */
        $context = $this->context;

        $validator = $context->getValidator()->inContext($context);
        $lastCount = $context->getViolations()->count();

        $count  = $lastCount;
        $passed = false;

        /** @noinspection PhpUndefinedFieldInspection */
        foreach ($constraint->constraints as $subconstraint) {

            $validator->validate($value, $subconstraint);
            $thisCount = $context->getViolations()->count();

            if ($thisCount === $lastCount) {
                $passed = true;
                break;
            }

            $lastCount = $thisCount;
        }

        if ($passed) {
            foreach ($context->getViolations() as $offset => $violation) {
                if (--$count < 0) {
                    $context->getViolations()->remove($offset);
                }
            }
        }
    }
}
