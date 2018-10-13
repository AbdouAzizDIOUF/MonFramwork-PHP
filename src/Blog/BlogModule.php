<?php
    namespace App\Blog;

    use Framework\Router;
    use Prs\Http\Message\ResponseInterface as Response;
    //use Prs\Http\Message\ServerRequestInterface as Request;
    use GuzzleHttp\Psr7\ServerRequest as Request;

class BlogModule
{

    public function __construct(Router $router)
    {
        $router->get('/blog', [$this, 'index'], 'blog.index');
        $router->get('/blog/{slug:[a-z\-]+}', [$this, 'show'], 'blog.show');
    }

    public function index(Request $request): string
    {
        return '<h1>Bienvenue sur le blog</h1>';
    }

    public function show(Request $request): string
    {
        return  '<h1>Bienvenue sur l\'article '. $request->getAttribute('slug') .'</h1>';
    }
}
