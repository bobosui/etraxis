<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2014-2016 Artem Rodygin
//
//  You should have received a copy of the GNU General Public License
//  along with the file. If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------

namespace eTraxis\SimpleBus\Groups\Handler;

use eTraxis\Entity\Group;
use eTraxis\SimpleBus\Groups\UpdateGroupCommand;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Command handler.
 */
class UpdateGroupCommandHandler
{
    protected $logger;
    protected $validator;
    protected $doctrine;

    /**
     * Dependency Injection constructor.
     *
     * @param   LoggerInterface    $logger
     * @param   ValidatorInterface $validator
     * @param   RegistryInterface  $doctrine
     */
    public function __construct(
        LoggerInterface    $logger,
        ValidatorInterface $validator,
        RegistryInterface  $doctrine)
    {
        $this->logger    = $logger;
        $this->validator = $validator;
        $this->doctrine  = $doctrine;
    }

    /**
     * Updates specified group.
     *
     * @param   UpdateGroupCommand $command
     *
     * @throws  BadRequestHttpException
     * @throws  NotFoundHttpException
     */
    public function handle(UpdateGroupCommand $command)
    {
        $repository = $this->doctrine->getRepository(Group::class);

        /** @var Group $entity */
        $entity = $repository->find($command->id);

        if (!$entity) {
            $this->logger->error('Unknown group.', [$command->id]);
            throw new NotFoundHttpException('Unknown group.');
        }

        $entity
            ->setName($command->name)
            ->setDescription($command->description)
        ;

        $errors = $this->validator->validate($entity);

        if (count($errors)) {
            $message = $errors->get(0)->getMessage();
            $this->logger->error($message);
            throw new BadRequestHttpException($message);
        }

        $this->doctrine->getManager()->persist($entity);
        $this->doctrine->getManager()->flush();
    }
}
