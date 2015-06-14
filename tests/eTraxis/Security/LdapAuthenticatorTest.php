<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2014-2015 Artem Rodygin
//
//  This file is part of eTraxis.
//
//  You should have received a copy of the GNU General Public License
//  along with eTraxis.  If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------


namespace eTraxis\Security;

use eTraxis\Service\LdapService;
use eTraxis\Tests\BaseTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class LdapAuthenticatorTest extends BaseTestCase
{
    public function testCreateToken()
    {
        /** @var \SimpleBus\Message\Bus\MessageBus $command_bus */
        $command_bus = self::$kernel->getContainer()->get('command_bus');

        $object = new LdapAuthenticator(
            $this->logger,
            $command_bus,
            new LdapService($this->logger, null),
            'DC=example,DC=com'
        );

        $token = $object->createToken(new Request(), 'einstein', 'password', 'etraxis_provider');

        $this->assertTrue($token instanceof UsernamePasswordToken);
    }

    public function testSupportsTokenSuccess()
    {
        /** @var \SimpleBus\Message\Bus\MessageBus $command_bus */
        $command_bus = self::$kernel->getContainer()->get('command_bus');

        $object = new LdapAuthenticator(
            $this->logger,
            $command_bus,
            new LdapService($this->logger, null),
            'DC=example,DC=com'
        );

        $token = new UsernamePasswordToken('einstein', 'password', 'etraxis_provider');

        $this->assertTrue($object->supportsToken($token, 'etraxis_provider'));
    }

    public function testSupportsTokenBadToken()
    {
        /** @var \SimpleBus\Message\Bus\MessageBus $command_bus */
        $command_bus = self::$kernel->getContainer()->get('command_bus');

        $object = new LdapAuthenticator(
            $this->logger,
            $command_bus,
            new LdapService($this->logger, null),
            'DC=example,DC=com'
        );

        $token = new PreAuthenticatedToken('einstein', 'password', 'etraxis_provider');

        $this->assertFalse($object->supportsToken($token, 'etraxis_provider'));
    }

    public function testSupportsTokenBadProvider()
    {
        /** @var \SimpleBus\Message\Bus\MessageBus $command_bus */
        $command_bus = self::$kernel->getContainer()->get('command_bus');

        $object = new LdapAuthenticator(
            $this->logger,
            $command_bus,
            new LdapService($this->logger, null),
            'DC=example,DC=com'
        );

        $token = new UsernamePasswordToken('einstein', 'password', 'etraxis_provider');

        $this->assertFalse($object->supportsToken($token, 'wrong_provider'));
    }

    public function testAuthenticateTokenSuccess()
    {
        /** @var \SimpleBus\Message\Bus\MessageBus $command_bus */
        $command_bus = self::$kernel->getContainer()->get('command_bus');

        $ldap = new LdapService(
            $this->logger,
            'ldap.forumsys.com',
            389,
            'CN=read-only-admin,DC=example,DC=com',
            'password'
        );

        $this->assertTrue($ldap->isConnected());

        $object = new LdapAuthenticator(
            $this->logger,
            $command_bus,
            $ldap,
            'DC=example,DC=com'
        );

        $provider = new InternalUserProvider($this->logger, $this->doctrine);
        $token    = new UsernamePasswordToken('einstein', 'password', 'etraxis_provider');

        $user = $object->authenticateToken($token, $provider, 'etraxis_provider');

        $this->assertTrue($user instanceof UsernamePasswordToken);
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\AuthenticationException
     */
    public function testAuthenticateTokenNoConnection()
    {
        /** @var \SimpleBus\Message\Bus\MessageBus $command_bus */
        $command_bus = self::$kernel->getContainer()->get('command_bus');

        $ldap = new LdapService($this->logger, null);

        $this->assertFalse($ldap->isConnected());

        $object = new LdapAuthenticator(
            $this->logger,
            $command_bus,
            $ldap,
            'DC=example,DC=com'
        );

        $provider = new InternalUserProvider($this->logger, $this->doctrine);

        $token = new UsernamePasswordToken('einstein', 'password', 'etraxis_provider');

        $object->authenticateToken($token, $provider, 'etraxis_provider');
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\AuthenticationException
     */
    public function testAuthenticateTokenWrongPassword()
    {
        /** @var \SimpleBus\Message\Bus\MessageBus $command_bus */
        $command_bus = self::$kernel->getContainer()->get('command_bus');

        $ldap = new LdapService(
            $this->logger,
            'ldap.forumsys.com',
            389,
            'CN=read-only-admin,DC=example,DC=com',
            'password'
        );

        $this->assertTrue($ldap->isConnected());

        $object = new LdapAuthenticator(
            $this->logger,
            $command_bus,
            $ldap,
            'DC=example,DC=com'
        );

        $provider = new InternalUserProvider($this->logger, $this->doctrine);

        $token = new UsernamePasswordToken('einstein', 'wrong', 'etraxis_provider');

        $object->authenticateToken($token, $provider, 'etraxis_provider');
    }
}
