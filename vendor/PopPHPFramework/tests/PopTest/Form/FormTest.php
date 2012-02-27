<?php
/**
 * Pop PHP Framework Unit Tests
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.TXT.
 * It is also available through the world-wide-web at this URL:
 * http://www.popphp.org/LICENSE.TXT
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@popphp.org so we can send you a copy immediately.
 *
 */

namespace PopTest\Form;

use Pop\Loader\Autoloader,
    Pop\Form\Form,
    Pop\Form\Element,
    Pop\Form\Element\Checkbox,
    Pop\Form\Element\Radio,
    Pop\Form\Element\Select,
    Pop\Form\Element\Textarea;

// Require the library's autoloader.
require_once __DIR__ . '/../../../src/Pop/Loader/Autoloader.php';

// Call the autoloader's bootstrap function.
Autoloader::factory()->splAutoloadRegister();

class FormTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructor()
    {
        $this->assertInstanceOf('Pop\\Form\\Form', new Form('/submit', 'post'));
    }

    public function testConstructorSetFields()
    {
        $fields = array(array(
            'type'       => 'text',
            'name'       => 'username',
            'value'      => 'Username here...',
            'label'      => 'Username:',
            'required'   => true,
            'attributes' => array('size', 40)
        ));
        $f = new Form('/submit', 'post', $fields);
        $this->assertEquals(1, count($f->getFields()));
    }

    public function testSetFieldsException()
    {
        $this->setExpectedException('Pop\\Form\\Exception');
        $fields = array(
            'type'       => 'text',
            'name'       => 'username',
            'value'      => 'Username here...',
            'label'      => 'Username:',
            'required'   => true,
            'attributes' => array('size', 40)
        );
        $f = new Form('/submit', 'post', $fields);
    }

    public function testSetFieldValues()
    {
        $fields = array(array(
            'type'       => 'text',
            'name'       => 'username',
            'value'      => 'Username here...',
            'label'      => 'Username:',
            'required'   => true,
            'attributes' => array('size', 40)
        ));
        $f = new Form('/submit', 'post', $fields);
        $f->setFieldValues(array('username' => 'testuser'), array('stripTags', 'html'));
        $this->assertEquals('testuser', $f->username);
    }

    public function testSetAndGetTemplate()
    {
        $f = new Form('/submit', 'post');
        $f->setTemplate('This is the template');
        $this->assertEquals('This is the template', $f->getTemplate());
    }

    public function testSetAndGetAttributes()
    {
        $f = new Form('/submit', 'post');
        $f->setAttributes('id', 'test-form');
        $this->assertEquals(3, count($f->getAttributes()));
    }

    public function testAddAndGetElements()
    {
        $e = new Element('text', 'username', 'Username');
        $f = new Form('/submit', 'post');
        $f->addElements($e);
        $this->assertEquals(1, count($f->getElements()));
        $this->assertInstanceOf('Pop\\Form\\Element', $f->getElement('username'));
    }

    public function testAddElements()
    {
        $e = new Element('text', 'username', 'Username');
        $c = new Checkbox('colors', array('Red', 'Green', 'Blue'));
        $r = new Radio('colors', array('Red', 'Green', 'Blue'));
        $s = new Select('colors', array('Red', 'Green', 'Blue'));
        $t = new Textarea('comments');
        $f = new Form('/submit', 'post');
        $f->addElements($e);
        $f->addElements(array($c, $r, $s, $t));
        $this->assertEquals(5, count($f->getElements()));
        $this->assertInstanceOf('Pop\\Form\\Element', $f->getElement('username'));
    }

    public function testRender()
    {
        $e = new Element('text', 'username', 'Username');
        $e->setLabel('Username');
        $s = new Element('submit', 'submit', 'Submit');
        $f = new Form('/submit', 'post');
        $f->addElements(array($e, $s));
        $form = $f->render(true);
        $this->assertContains('<form ', $form);

        $f = new Form('/submit', 'post');
        $f->addElements(array($e, $s));
        ob_start();
        $f->render();
        $output = ob_get_clean();
        $this->assertContains('<form ', $output);

        $f = new Form('/submit', 'post');
        $f->addElements(array($e, $s));
        $this->assertContains('<form ', (string)$f);
    }

    public function testRenderException()
    {
        $this->setExpectedException('Pop\\Form\Exception');
        $f = new Form('/submit', 'post');
        $f->render();
    }

}

