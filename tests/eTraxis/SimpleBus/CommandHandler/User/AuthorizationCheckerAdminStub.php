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


namespace eTraxis\SimpleBus\CommandHandler\User;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AuthorizationCheckerAdminStub implements AuthorizationCheckerInterface
{
    public function isGranted($attributes, $object = null)
    {
        return $attributes == 'ROLE_ADMIN';
    }
}