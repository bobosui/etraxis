<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2015 Artem Rodygin
//
//  You should have received a copy of the GNU General Public License
//  along with the file. If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------

namespace eTraxis\SimpleBus\Templates\Handler;

use eTraxis\SimpleBus\CommandException;
use eTraxis\SimpleBus\Templates\UpdateTemplateCommand;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Command handler.
 */
class UpdateTemplateCommandHandler
{
    protected $logger;
    protected $validator;
    protected $translator;
    protected $doctrine;

    /**
     * Dependency Injection constructor.
     *
     * @param   LoggerInterface     $logger
     * @param   ValidatorInterface  $validator
     * @param   TranslatorInterface $translator
     * @param   RegistryInterface   $doctrine
     */
    public function __construct(
        LoggerInterface     $logger,
        ValidatorInterface  $validator,
        TranslatorInterface $translator,
        RegistryInterface   $doctrine)
    {
        $this->logger     = $logger;
        $this->validator  = $validator;
        $this->translator = $translator;
        $this->doctrine   = $doctrine;
    }

    /**
     * Updates specified template.
     *
     * @param   UpdateTemplateCommand $command
     *
     * @throws  CommandException
     * @throws  NotFoundHttpException
     */
    public function handle(UpdateTemplateCommand $command)
    {
        $repository = $this->doctrine->getRepository('eTraxis:Template');

        /** @var \eTraxis\Entity\Template $entity */
        $entity = $repository->find($command->id);

        if (!$entity) {
            $this->logger->error('Unknown template.', [$command->id]);
            throw new NotFoundHttpException('Unknown template.');
        }

        $entity
            ->setName($command->name)
            ->setPrefix($command->prefix)
            ->setDescription($command->description)
            ->setCriticalAge($command->criticalAge)
            ->setFrozenTime($command->frozenTime)
            ->setGuestAccess($command->guestAccess)
        ;

        $errors = $this->validator->validate($entity);

        if (count($errors)) {
            $message = $this->translator->trans($errors->get(0)->getMessage());
            $this->logger->error($message);
            throw new CommandException($message);
        }

        $this->doctrine->getManager()->persist($entity);
        $this->doctrine->getManager()->flush();
    }
}