<?php
namespace Test\App\Blog\Actions;

use App\Blog\Actions\BlogAction;
use App\Blog\Table\PostTable;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

class BlogActionTest extends TestCase
{

    private $action;
    private $renderer;
    private $postTable;
    private $router;

    public function setUp()
    {
        $this->renderer = $this->prophesize(RendererInterface::class);
        $this->postTable = $this->prophesize(PostTable::class);
        $this->router = $this->prophesize(Router::class);
        $this->action = new BlogAction(
            $this->renderer->reveal(),
            $this->router->reveal(),
            $this->postTable->reveal()
        );
    }

    public function makePost(int $id, string $slug): \stdClass
    {
        $post = new \stdClass();
        $post->id = $id;
        $post->slug = $slug;
        return $post;
    }

    public function testShowRedirect()
    {
        $post = $this->makePost(9, "vdchgsvchsdsgvgsvg-gv");
        $request = (new ServerRequest('GET', '/'))
                ->withAttribute('id', 9)
                ->withAttribute('slug', 'demo');
        $this->router->generateUri(
            'blog.show',
            ['id' => $post->id, 'slug' =>  $post->slug]
        )->willReturn('/demo2');

        $this->postTable->find($post->id)->willReturn($post);

        $response = call_user_func_array($this->action, [$request]);
        $this->assertEquals(301, $response->getStatusCode());
        $this->assertEquals(['/demo2'], $response->getHeader('location'));
    }

    public function testShowRenderer()
    {
        $post = $this->makePost(9, "vdchgsvchsdsgvgsvg-gv");
        $request = (new ServerRequest('GET', '/'))
                ->withAttribute('id', $post->id)
                ->withAttribute('slug', $post->slug);
        $this->postTable->find($post->id)->willReturn($post);
        $this->renderer->render('@blog/show', ['post' => $post])->willReturn('');

        $response = call_user_func_array($this->action, [$request]);
        $this->assertEquals(true, true);
    }


    /*
    public function setUp()
    {
        $this->renderer = $this->prophesize(RendererInterface::class);
        $this->renderer->render(Argument::any())->willReturn('');
        //Article
        $post = new \stdClass();
        $post->id = 9;
        $post->slug = 'demo-test';
        //PDO
        $this->pdo = $this->prophesize(\PDO::class);
        $pdoStatement = $this->prophesize(\PDOStatement::class);
        $this->pdo->prepare(Argument::any())->willReturn($pdoStatement);
        $this->pdo->execute(Argument::any())->willReturn(null);
        $pdoStatement->fetch()->willReturn($post);
        $this->router = $this->prophesize(Router::class);
        $this->action = new BlogAction(
            $this->renderer->reveal(),
            $this->pdo->reveal(),
            $this->router->reveal()
        );
    }

    public function testShowRedirect()
    {
        $this->router->generateUri('blog.show', ['id' => 9, 'slug' => 'demo-test'])->willReturn('/demo2');
        $request = (new ServerRequest('GET', '/'))
            ->withAttribute('id', 9)
            ->withAttribute('slug', 'demo');
        $response = call_user_func_array($this->action, [$request]);
        $this->assertEquals(301, $response->getStatusCode());
        $this->assertEquals(['/demo2'], $response->getHeader('location'));
    }
     */
}
