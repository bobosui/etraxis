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
use eTraxis\SimpleBus\Users\CreateUserCommand;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Command handler.
 */
class CreateUserCommandHandler
{
    protected $logger;
    protected $validator;
    protected $doctrine;
    protected $password_encoder;

    /**
     * Dependency Injection constructor.
     *
     * @param   LoggerInterface          $logger
     * @param   ValidatorInterface       $validator
     * @param   RegistryInterface        $doctrine
     * @param   PasswordEncoderInterface $password_encoder
     */
    public function __construct(
        LoggerInterface          $logger,
        ValidatorInterface       $validator,
        RegistryInterface        $doctrine,
        PasswordEncoderInterface $password_encoder)
    {
        $this->logger           = $logger;
        $this->validator        = $validator;
        $this->doctrine         = $doctrine;
        $this->password_encoder = $password_encoder;
    }

    /**
     * Creates new account.
     *
     * @param   CreateUserCommand $command
     *
     * @throws  BadRequestHttpException
     */
    public function handle(CreateUserCommand $command)
    {
        try {
            $encoded = $this->password_encoder->encodePassword($command->password, null);
        }
        catch (BadCredentialsException $e) {
            $this->logger->error($e->getMessage());
            throw new BadRequestHttpException($e->getMessage());
        }

        $entity = new User();

        $entity
            ->setUsername($command->username)
            ->setFullname($command->fullname)
            ->setEmail($command->email)
            ->setDescription($command->description)
            ->setPassword($encoded)
            ->setPasswordSetAt(time())
            ->setAdmin($command->admin)
            ->setDisabled($command->disabled)
            ->setLdap(false)
            ->setLocale($command->locale)
            ->setTheme($command->theme)
            ->setTimezone($command->timezone)
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
