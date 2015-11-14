<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2015 Artem Rodygin
//
//  You should have received a copy of the GNU General Public License
//  along with the file. If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------

namespace eTraxis\SimpleBus\Users;

use eTraxis\Traits\ObjectInitiationTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Resets password for specified account.
 *
 * @property    string $token    Token for password reset.
 * @property    string $password New password.
 */
class ResetPasswordCommand
{
    use ObjectInitiationTrait;

    /**
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^[a-z0-9]{32}$/i");
     */
    public $token;

    /**
     * @Assert\NotBlank()
     */
    public $password;
}
