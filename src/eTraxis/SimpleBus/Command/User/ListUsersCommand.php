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


namespace eTraxis\SimpleBus\Command\User;

use eTraxis\SimpleBus\CommandTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Enumerates all accounts existing in eTraxis database.
 *
 * Input properties:
 *
 * @property    int   $start  First row to return, zero-based.
 * @property    int   $length Total number of rows to return.
 * @property    array $search Current search value.
 * @property    array $order  Current columns ordering.
 *
 * Output properties:
 *
 * @property    int   $total Total number of rows in the list.
 * @property    array $users List of users.
 */
class ListUsersCommand
{
    use CommandTrait;

    /**
     * @Assert\NotNull()
     * @Assert\GreaterThanOrEqual(value="0")
     */
    protected $start = 0;

    /**
     * @Assert\NotNull()
     * @Assert\GreaterThanOrEqual(value="-1")
     */
    protected $length = -1;

    /**
     * @Assert\NotNull()
     * @Assert\Collection(
     *     fields = {
     *         "value" = @Assert\Type(type="string")
     *     },
     *     allowExtraFields   = true,
     *     allowMissingFields = true
     * )
     */
    protected $search = [];

    /**
     * @Assert\NotNull()
     * @Assert\Type(type="array")
     * @Assert\All({
     *     @Assert\Collection(
     *         fields = {
     *             "column" = {
     *                 @Assert\GreaterThanOrEqual(value="0"),
     *                 @Assert\LessThan(value="5")
     *             },
     *             "dir" = @Assert\Type(type="string")
     *         },
     *         allowExtraFields   = false,
     *         allowMissingFields = false
     *     )
     * })
     */
    protected $order = [];

    protected $total = 0;

    protected $users = [];
}