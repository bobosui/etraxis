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

use eTraxis\Entity\Project;
use eTraxis\Tests\BaseTestCase;

class ProjectVoterTest extends BaseTestCase
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

        /** @var Project $project */
        $project = $this->doctrine->getRepository('eTraxis:Project')->findOneBy(['name' => 'Planet Express']);

        $this->assertFalse($this->security->isGranted('UNKNOWN', $project));
    }

    public function testDelete()
    {
        $this->loginAs('hubert');

        /** @var Project $project */
        $project = $this->doctrine->getRepository('eTraxis:Project')->findOneBy(['name' => 'Planet Express']);

        /** @var Project $project */
        $empty = $this->doctrine->getRepository('eTraxis:Project')->findOneBy(['name' => 'eTraxis 1.0']);

        $this->assertInstanceOf('eTraxis\Entity\Project', $project);
        $this->assertInstanceOf('eTraxis\Entity\Project', $empty);

        $this->assertFalse($this->security->isGranted(Project::DELETE, $project));
        $this->assertTrue($this->security->isGranted(Project::DELETE, $empty));
    }
}
