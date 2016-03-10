<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2014-2016 Artem Rodygin
//
//  You should have received a copy of the GNU General Public License
//  along with the file. If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------

namespace eTraxis\Voter;

use eTraxis\Entity\Event;
use eTraxis\Entity\User;
use eTraxis\Tests\BaseTestCase;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

class UserVoterTest extends BaseTestCase
{
    /** @var \Symfony\Component\Security\Core\Authorization\AuthorizationChecker */
    private $security = null;

    protected function setUp()
    {
        parent::setUp();

        $this->security = $this->client->getContainer()->get('security.authorization_checker');
    }

    public function testUnsupportedAttribute()
    {
        $this->loginAs('hubert');

        $hubert = $this->findUser('hubert');

        $this->assertFalse($this->security->isGranted('UNKNOWN', $hubert));
    }

    public function testAnonymous()
    {
        $scruffy = $this->findUser('scruffy');

        /** @var \eTraxis\Repository\EventsRepository $repository */
        $repository = $this->doctrine->getRepository(Event::class);

        $voter = new UserVoter($repository);
        $token = new AnonymousToken('', 'anon.');

        $this->assertEquals(UserVoter::ACCESS_DENIED, $voter->vote($token, $scruffy, [User::DELETE, User::DISABLE]));
    }

    public function testSetExpiredPassword()
    {
        $this->loginAs('hubert');

        /** @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage $storage */
        $storage = $this->client->getContainer()->get('security.token_storage');
        $token   = $storage->getToken();

        $hubert = $this->findUser('hubert');

        $this->assertFalse($this->security->isGranted(User::SET_EXPIRED_PASSWORD, $hubert));

        $hubert->setPasswordSetAt(time() - 86400 * 2);

        /** @var \eTraxis\Repository\EventsRepository $repository */
        $repository = $this->doctrine->getRepository(Event::class);

        $voter = new UserVoter($repository, 3);
        $this->assertEquals(UserVoter::ACCESS_DENIED, $voter->vote($token, $hubert, [User::SET_EXPIRED_PASSWORD]));

        $voter = new UserVoter($repository, 1);
        $this->assertEquals(UserVoter::ACCESS_GRANTED, $voter->vote($token, $hubert, [User::SET_EXPIRED_PASSWORD]));
    }

    public function testDelete()
    {
        $this->loginAs('hubert');

        $hubert  = $this->findUser('hubert');
        $leela   = $this->findUser('leela');
        $scruffy = $this->findUser('scruffy');

        $this->assertInstanceOf(User::class, $hubert);
        $this->assertInstanceOf(User::class, $leela);
        $this->assertInstanceOf(User::class, $scruffy);

        $this->assertFalse($this->security->isGranted(User::DELETE, $hubert));
        $this->assertFalse($this->security->isGranted(User::DELETE, $leela));
        $this->assertTrue($this->security->isGranted(User::DELETE, $scruffy));
    }

    public function testDisable()
    {
        $this->loginAs('hubert');

        $hubert   = $this->findUser('hubert');
        $francine = $this->findUser('francine');
        $scruffy  = $this->findUser('scruffy');

        $this->assertInstanceOf(User::class, $hubert);
        $this->assertInstanceOf(User::class, $francine);
        $this->assertInstanceOf(User::class, $scruffy);

        $this->assertFalse($this->security->isGranted(User::DISABLE, $hubert));
        $this->assertFalse($this->security->isGranted(User::DISABLE, $francine));
        $this->assertTrue($this->security->isGranted(User::DISABLE, $scruffy));
    }

    public function testEnable()
    {
        $this->loginAs('hubert');

        $hubert   = $this->findUser('hubert');
        $francine = $this->findUser('francine');

        $this->assertInstanceOf(User::class, $hubert);
        $this->assertInstanceOf(User::class, $francine);

        $this->assertFalse($this->security->isGranted(User::ENABLE, $hubert));
        $this->assertTrue($this->security->isGranted(User::ENABLE, $francine));
    }

    public function testUnlock()
    {
        $this->loginAs('hubert');

        $hubert = $this->findUser('hubert');
        $bender = $this->findUser('bender');

        $this->assertInstanceOf(User::class, $hubert);
        $this->assertInstanceOf(User::class, $bender);

        $bender->setAuthAttempts(3);
        $bender->setLockedUntil(time() + 60);

        $this->assertTrue($this->security->isGranted(User::UNLOCK, $bender));
        $this->assertFalse($this->security->isGranted(User::UNLOCK, $hubert));
    }
}
