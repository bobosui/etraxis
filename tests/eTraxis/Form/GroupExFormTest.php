<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2014-2016 Artem Rodygin
//
//  You should have received a copy of the GNU General Public License
//  along with the file. If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------

namespace eTraxis\Form;

use eTraxis\Tests\BaseTestCase;

class GroupExFormTest extends BaseTestCase
{
    public function testForm()
    {
        $expected = mt_rand(1, PHP_INT_MAX);

        /** @var \Symfony\Component\Form\FormFactoryInterface $factory */
        $factory = $this->client->getContainer()->get('form.factory');

        $form = $factory->create(GroupExForm::class, ['id' => $expected]);
        $view = $form->createView();

        self::assertEquals($view->vars['data']['id'], $expected);
    }
}
