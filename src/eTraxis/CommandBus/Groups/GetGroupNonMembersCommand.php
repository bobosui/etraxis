<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2015 Artem Rodygin
//
//  You should have received a copy of the GNU General Public License
//  along with the file. If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------

namespace eTraxis\CommandBus\Groups;

use eTraxis\Traits\ObjectInitiationTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Returns all accounts which doesn't belong to the specified group.
 *
 * @property    int $id Group ID.
 */
class GetGroupNonMembersCommand
{
    use ObjectInitiationTrait;

    /**
     * @Assert\NotBlank()
     * @eTraxis\Validator\EntityIdConstraint()
     */
    public $id;
}
