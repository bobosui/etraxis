<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2015 Artem Rodygin
//
//  You should have received a copy of the GNU General Public License
//  along with the file. If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------

namespace eTraxis\SimpleBus\Fields\Handler;

use eTraxis\Entity\Field;
use eTraxis\SimpleBus\CommandException;
use eTraxis\SimpleBus\Fields\CreateCheckboxFieldCommand;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Command handler.
 */
class CreateCheckboxFieldCommandHandler extends BaseFieldCommandHandler
{
    /**
     * Creates new "checkbox" field.
     *
     * @param   CreateCheckboxFieldCommand $command
     *
     * @throws  CommandException
     * @throws  NotFoundHttpException
     */
    public function handle(CreateCheckboxFieldCommand $command)
    {
        $entity = $this->create($command);

        $entity
            ->setType(Field::TYPE_CHECKBOX)
            ->setParameter1(null)
            ->setParameter2(null)
            ->setDefaultValue($command->default)
        ;

        $this->doctrine->getManager()->persist($entity);
        $this->doctrine->getManager()->flush();
    }
}
