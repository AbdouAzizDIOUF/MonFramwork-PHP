<?php
    namespace App\Blog;

    use Framework\Router;
    use Prs\Http\Message\ResponseInterface as Response;
    //use Prs\Http\Message\ServerRequestInterface as Request;
    use GuzzleHttp\Psr7\ServerRequest as Request;

class BlogModule
{

    private $renderer;

    public function __construct(Router $router, \Framework\Renderer $renderer)
    {
        $this->renderer = $renderer;
        $this->renderer->addPath('blog', __DIR__ . '/views');
        $router->get('/blog', [$this, 'index'], 'blog.index');
        $router->get('/blog/{slug:[a-z\-0-9]+}', [$this, 'show'], 'blog.show');
    }

    public function index(Request $request): string
    {
        return $this->renderer->render('@blog/index');
    }

    public function show(Request $request): string
    {
        return $this->renderer->render('@blog/show', ['slug' => $request->getAttribute('slug')]);
    }
}
