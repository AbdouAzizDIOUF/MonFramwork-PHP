<?php
namespace Tests\Framework;

use PHPUnit\Framework\TestCase;
use Framework\Renderer;

class RendererTest extends TestCase{

    private $renderer;

    public function setUp()
    {
        $this->renderer = new Renderer();
        $this->renderer->addPath(__DIR__. '/views');
    }

    public function testRendereTheRightPath()
    {
        $this->renderer->addPath('blog', __DIR__. '/views');
        $content = $this->renderer->render("@blog/demo");
        $this->assertEquals('salut', $content);
    }

    public function testRendereTheDefaultPath()
    {
        $content = $this->renderer->render('demo');
        $this->assertEquals('salut', $content);
    }

    public function testRendereTheWithPath()
    {
        $content = $this->renderer->render('demoparams', ['nom' => 'ziz']);
        $this->assertEquals('salut ziz', $content);
    }

    public function testGlobalParameters()
    {
        $this->renderer->addGlobal('nom', 'ziz');
        $content = $this->renderer->render('demoparams');
        $this->assertEquals('salut ziz', $content);
    }
}
?>
