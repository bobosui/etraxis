<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2016 Artem Rodygin
//
//  You should have received a copy of the GNU General Public License
//  along with the file. If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------

namespace eTraxis\Repository;

use eTraxis\Tests\BaseTestCase;

class ProjectsRepositoryTest extends BaseTestCase
{
    public function testGetGroupMembersFound()
    {
        /** @var ProjectsRepository $repository */
        $repository = $this->doctrine->getManager()->getRepository('eTraxis:Project');

        $result = $repository->getProjects();

        $projects = array_map(function ($project) {
            return $project['name'];
        }, $result);

        $expected = [
            'eTraxis 1.0',
            'eTraxis 2.0',
            'eTraxis 3.0',
            'Planet Express',
        ];

        $this->assertEquals($expected, $projects);
    }
}
