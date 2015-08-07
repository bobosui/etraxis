<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2015 Artem Rodygin
//
//  This file is part of eTraxis.
//
//  You should have received a copy of the GNU General Public License
//  along with eTraxis.  If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------

namespace eTraxis\CommandBus\Groups\Handler;

use eTraxis\CommandBus\Groups\AddUsersCommand;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Command handler.
 */
class AddUsersCommandHandler
{
    protected $logger;
    protected $doctrine;

    /**
     * Dependency Injection constructor.
     *
     * @param   LoggerInterface   $logger
     * @param   RegistryInterface $doctrine
     */
    public function __construct(LoggerInterface $logger, RegistryInterface $doctrine)
    {
        $this->logger   = $logger;
        $this->doctrine = $doctrine;
    }

    /**
     * Adds specified accounts to group.
     *
     * @param   AddUsersCommand $command
     *
     * @throws  NotFoundHttpException
     */
    public function handle(AddUsersCommand $command)
    {
        /** @var \Doctrine\ORM\EntityRepository $repository */
        $repository = $this->doctrine->getRepository('eTraxis:Group');

        /** @var \eTraxis\Entity\Group $group */
        $group = $repository->find($command->id);

        if (!$group) {
            $this->logger->error('Unknown group.', [$command->id]);
            throw new NotFoundHttpException('Unknown group.');
        }

        $repository = $this->doctrine->getRepository('eTraxis:User');

        $query = $repository->createQueryBuilder('u');

        $query
            ->select('u')
            ->where($query->expr()->in('u.id', ':users'))
            ->setParameter('users', $command->users)
        ;

        /** @var \eTraxis\Entity\User[] $users */
        $users = $query->getQuery()->getResult();

        foreach ($users as $user) {
            $group->addUser($user);
        }

        $this->doctrine->getManager()->persist($group);
        $this->doctrine->getManager()->flush();
    }
}
