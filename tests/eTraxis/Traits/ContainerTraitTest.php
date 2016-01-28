<?php

//----------------------------------------------------------------------
//
//  Copyright (C) 2014-2016 Artem Rodygin
//
//  You should have received a copy of the GNU General Public License
//  along with the file. If not, see <http://www.gnu.org/licenses/>.
//
//----------------------------------------------------------------------

namespace eTraxis\Traits;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

class ControllerStub extends Controller
{
    use ClassAccessTrait;
    use ContainerTrait;
}

class ContainerTraitTest extends KernelTestCase
{
    /** @var ControllerStub */
    private $object = null;

    protected function setUp()
    {
        self::bootKernel();

        $this->object = new ControllerStub();
        $this->object->setContainer(static::$kernel->getContainer());
    }

    protected function tearDown()
    {
        unset($this->object);

        parent::tearDown();
    }

    public function testGetFormDataSuccessGet()
    {
        /** @var \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface $csrf */
        $csrf = static::$kernel->getContainer()->get('security.csrf.token_manager');
        $csrf->refreshToken('form');

        $formdata = [
            '_token' => $csrf->getToken('form')->getValue(),
            'fname'  => 'Artem',
            'lname'  => 'Rodygin',
            'empty'  => '',
        ];

        $request = new Request(['form' => $formdata]);

        $formdata['empty'] = null;

        $this->assertEquals($formdata, $this->object->getFormData($request));
    }

    public function testGetFormDataSuccessPost()
    {
        /** @var \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface $csrf */
        $csrf = static::$kernel->getContainer()->get('security.csrf.token_manager');
        $csrf->refreshToken('user');

        $formdata = [
            '_token' => $csrf->getToken('user')->getValue(),
            'fname'  => 'Artem',
            'lname'  => 'Rodygin',
            'empty'  => '',
        ];

        $request = new Request([], ['user' => $formdata]);
        $request->setMethod(Request::METHOD_POST);

        $formdata['empty'] = null;

        $this->assertEquals($formdata, $this->object->getFormData($request, 'user'));
    }

    public function testGetFormDataExtra()
    {
        /** @var \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface $csrf */
        $csrf = static::$kernel->getContainer()->get('security.csrf.token_manager');
        $csrf->refreshToken('form');

        $formdata = [
            '_token' => $csrf->getToken('form')->getValue(),
            'fname'  => 'Artem',
            'lname'  => 'Rodygin',
        ];

        $request = new Request(['form' => $formdata]);

        $formdata['sex'] = 'male';

        $this->assertEquals($formdata, $this->object->getFormData($request, 'form', ['sex' => 'male']));

        $formdata['lname'] = 'R.';
        unset($formdata['sex']);

        $this->assertEquals($formdata, $this->object->getFormData($request, 'form', ['lname' => 'R.']));
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage No data submitted.
     */
    public function testGetFormDataNoData()
    {
        /** @var \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface $csrf */
        $csrf = static::$kernel->getContainer()->get('security.csrf.token_manager');
        $csrf->refreshToken('user');

        $formdata = [
            '_token' => $csrf->getToken('user')->getValue(),
            'fname'  => 'Artem',
            'lname'  => 'Rodygin',
        ];

        $request = new Request(['user' => $formdata]);

        $this->object->getFormData($request);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage Invalid CSRF token.
     */
    public function testGetFormDataInvalidCsrf()
    {
        /** @var \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface $csrf */
        $csrf = static::$kernel->getContainer()->get('security.csrf.token_manager');
        $csrf->refreshToken('user');
        $csrf->refreshToken('form');

        $formdata = [
            '_token' => $csrf->getToken('user')->getValue(),
            'fname'  => 'Artem',
            'lname'  => 'Rodygin',
        ];

        $request = new Request(['form' => $formdata]);

        $this->object->getFormData($request);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage CSRF token is missing.
     */
    public function testGetFormDataNoCsrf()
    {
        $formdata = [
            'fname'  => 'Artem',
            'lname'  => 'Rodygin',
        ];

        $request = new Request(['form' => $formdata]);

        $this->object->getFormData($request);
    }

    public function testSetNotice()
    {
        /** @var \Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface $flashBag */
        $flashBag = $this->object->container->get('session')->getFlashBag();
        $flashBag->clear();

        $this->object->setNotice('Information');

        $this->assertTrue($flashBag->has('notice'));
        $this->assertCount(1, $flashBag->get('notice'));
        $this->assertCount(0, $flashBag->get('notice'));
    }

    public function testSetError()
    {
        /** @var \Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface $flashBag */
        $flashBag = $this->object->container->get('session')->getFlashBag();
        $flashBag->clear();

        $this->object->setError('Error');

        $this->assertTrue($flashBag->has('error'));
        $this->assertCount(1, $flashBag->get('error'));
        $this->assertCount(0, $flashBag->get('error'));
    }

    public function testTranslator()
    {
        $this->assertInstanceOf('\Symfony\Component\Translation\TranslatorInterface', $this->object->getTranslator());
    }

    public function testGetCommandBus()
    {
        $this->assertInstanceOf('\SimpleBus\Message\Bus\MessageBus', $this->object->getCommandBus());
    }

    public function testGetEventBus()
    {
        $this->assertInstanceOf('\SimpleBus\Message\Bus\MessageBus', $this->object->getEventBus());
    }

    public function testGetDataTables()
    {
        $this->assertInstanceOf('\DataTables\DataTablesInterface', $this->object->getDataTables());
    }
}
