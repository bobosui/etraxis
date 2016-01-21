<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2014-2016 Artem Rodygin
//
//  You should have received a copy of the GNU General Public License
//  along with the file. If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------

namespace eTraxis\Service;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MailerServiceTest extends KernelTestCase
{
    protected function setUp()
    {
        self::bootKernel();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testSend()
    {
        $logger = static::$kernel->getContainer()->get('logger');
        $twig   = static::$kernel->getContainer()->get('twig');
        $mailer = static::$kernel->getContainer()->get('mailer');

        /** @noinspection PhpParamsInspection */
        $service = new MailerService($logger, $twig, $mailer);

        $sent = $service->send(
            'noreply@example.com',
            'eTraxis Support',
            'test@example.com',
            'Test',
            'email.html.twig'
        );

        $this->assertEquals(1, $sent);
    }
}
