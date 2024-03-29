<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2014-2016 Artem Rodygin
//
//  You should have received a copy of the GNU General Public License
//  along with the file. If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------

namespace eTraxis\SimpleBus\Users\Handler;

use eTraxis\Entity\User;
use eTraxis\SimpleBus\Users\UpdateUserCommand;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Command handler.
 */
class UpdateUserCommandHandler
{
    protected $logger;
    protected $validator;
    protected $doctrine;
    protected $token_storage;

    /**
     * Dependency Injection constructor.
     *
     * @param   LoggerInterface       $logger
     * @param   ValidatorInterface    $validator
     * @param   RegistryInterface     $doctrine
     * @param   TokenStorageInterface $token_storage
     */
    public function __construct(
        LoggerInterface       $logger,
        ValidatorInterface    $validator,
        RegistryInterface     $doctrine,
        TokenStorageInterface $token_storage)
    {
        $this->logger        = $logger;
        $this->validator     = $validator;
        $this->doctrine      = $doctrine;
        $this->token_storage = $token_storage;
    }

    /**
     * Updates specified account.
     *
     * @param   UpdateUserCommand $command
     *
     * @throws  BadRequestHttpException
     * @throws  NotFoundHttpException
     */
    public function handle(UpdateUserCommand $command)
    {
        /** @var User $user */
        $user = $this->token_storage->getToken()->getUser();

        $repository = $this->doctrine->getRepository(User::class);

        /** @var User $entity */
        $entity = $repository->find($command->id);

        if (!$entity) {
            $this->logger->error('Unknown user.', [$command->id]);
            throw new NotFoundHttpException('Unknown user.');
        }

        $entity
            ->setUsername($command->username)
            ->setFullname($command->fullname)
            ->setEmail($command->email)
            ->setDescription($command->description)
            ->setLocale($command->locale)
            ->setTheme($command->theme)
            ->setTimezone($command->timezone)
        ;

        // Don't disable yourself.
        if ($entity->getId() !== $user->getId()) {
            $entity
                ->setAdmin($command->admin)
                ->setDisabled($command->disabled)
            ;
        }

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
