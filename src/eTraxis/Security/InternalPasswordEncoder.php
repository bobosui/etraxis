<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2014 Artem Rodygin
//
//  This file is part of eTraxis.
//
//  You should have received a copy of the GNU General Public License
//  along with eTraxis.  If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------

namespace eTraxis\Security;

use Symfony\Component\Security\Core\Encoder\BasePasswordEncoder;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

/**
 * eTraxis legacy password encoder.
 *
 * Up to version 3.6.7 passwords were stored as MD5 hashes which took 32 chars.
 * As of 3.6.8 passwords were stored as base64-encoded binary SHA1 hashes which took 28 chars.
 * For backward compatibility we let user authenticate if his password is stored in a legacy way.
 */
class InternalPasswordEncoder extends BasePasswordEncoder
{
    /**
     * {@inheritDoc}
     */
    public function encodePassword($raw, $salt = null)
    {
        if ($this->isPasswordTooLong($raw)) {
            throw new BadCredentialsException('Invalid password.');
        }

        return base64_encode(sha1($raw, true));
    }

    /**
     * {@inheritDoc}
     */
    public function isPasswordValid($encoded, $raw, $salt = null)
    {
        // base64-encoded binary SHA1 hash
        if (strlen($encoded) == 28) {
            return $this->comparePasswords($encoded, $this->encodePassword($raw, $salt));
        }

        // MD5 hash
        if (strlen($encoded) == 32) {
            return $this->comparePasswords($encoded, md5($raw));
        }

        return false;
    }
}
