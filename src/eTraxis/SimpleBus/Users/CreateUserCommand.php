<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2014-2016 Artem Rodygin
//
//  You should have received a copy of the GNU General Public License
//  along with the file. If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------

namespace eTraxis\SimpleBus\Users;

use SimpleBus\MessageTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Creates new account.
 *
 * @property    string $username    Login.
 * @property    string $fullname    Full name.
 * @property    string $email       Email address.
 * @property    string $description Description.
 * @property    string $password    Password.
 * @property    string $locale      Locale.
 * @property    string $theme       Theme.
 * @property    int    $timezone    Timezone.
 * @property    bool   $admin       Role (whether has administrator permissions).
 * @property    bool   $disabled    Status.
 */
class CreateUserCommand
{
    use MessageTrait;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max = "112")
     * @Assert\Regex(pattern="/^[a-z0-9_\.\-]+$/i", message="user.invalid.username");
     */
    public $username;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max = "64")
     */
    public $fullname;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max = "50")
     * @Assert\Email()
     */
    public $email;

    /**
     * @Assert\Length(max = "100")
     */
    public $description;

    /**
     * @Assert\NotBlank()
     */
    public $password;

    /**
     * @Assert\NotNull()
     * @Assert\Choice(callback = {"eTraxis\Collection\Locale", "getAllKeys"})
     */
    public $locale;

    /**
     * @Assert\NotNull()
     * @Assert\Choice(callback = {"eTraxis\Collection\Theme", "getAllKeys"})
     */
    public $theme;

    /**
     * @Assert\NotNull()
     * @Assert\Choice(callback = {"eTraxis\Collection\Timezone", "getAllKeys"})
     */
    public $timezone;

    /**
     * @Assert\NotNull()
     */
    public $admin;

    /**
     * @Assert\NotNull()
     */
    public $disabled;
}
