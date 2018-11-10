<?php
    namespace App\Blog;

    use Framework\Router;
    use App\Blog\Actions\BlogAction;
    use Framework\Renderer\RendererInterface;

class BlogModule extends \Framework\Module
{
    const DEFINITIONS = __DIR__ . '/config.php';
    const MIGRATIONS = __DIR__ . '/db/migrations';
    const SEEDS = __DIR__. '/db/seeds';

    public function __construct(string $prefix, Router $router, RendererInterface $renderer)
    {
        $renderer->addPath('blog', __DIR__ . '/views');
        $router->addRoute($prefix, BlogAction::class, 'blog.index');
        $router->addRoute($prefix . '/{slug:[a-z\-0-9]+}-{id:[0-9]+}', BlogAction::class, 'blog.show');
    }
}
