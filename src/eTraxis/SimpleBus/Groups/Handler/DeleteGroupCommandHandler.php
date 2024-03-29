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
use eTraxis\SimpleBus\Groups\DeleteGroupCommand;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Command handler.
 */
class DeleteGroupCommandHandler
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
     * Deletes specified group.
     *
     * @param   DeleteGroupCommand $command
     *
     * @throws  NotFoundHttpException
     */
    public function handle(DeleteGroupCommand $command)
    {
        $repository = $this->doctrine->getRepository(Group::class);

        $entity = $repository->find($command->id);

        if (!$entity) {
            $this->logger->error('Unknown group.', [$command->id]);
            throw new NotFoundHttpException('Unknown group.');
        }

        $this->doctrine->getManager()->remove($entity);
        $this->doctrine->getManager()->flush();
    }
}
