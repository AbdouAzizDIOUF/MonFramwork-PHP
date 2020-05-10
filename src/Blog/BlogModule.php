<?php
    namespace App\Blog;

use App\Blog\Actions\CategorieCrudAction;
use App\Blog\Actions\PostCrudAction;
use App\Blog\Actions\BlogAction;
use Framework\Module;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Container\ContainerInterface;

class BlogModule extends Module
{
    public const DEFINITIONS = __DIR__ . '/config.php';
    public const MIGRATIONS = __DIR__ . '/db/migrations';
    public const SEEDS = __DIR__. '/db/seeds';

    public function __construct(ContainerInterface $container)
    {
        $blogPrefix = $container->get('blog.prefix');
        $container->get(RendererInterface::class)->addPath('blog', __DIR__ . '/views');
        $container->get(Router::class)->addRoute($blogPrefix, BlogAction::class, 'blog.index');
        $container->get(Router::class)->addRoute($blogPrefix . '/{slug:[a-z\-0-9]+}-{id:[0-9]+}', BlogAction::class, 'blog.show');

        /*if ($container->has('admin.prefix')) {
            $prefix = $container->get('admin.prefix');
            $container->get(Router::class)->crud("$prefix/posts", PostCrudAction::class, 'blog.admin');
            $container->get(Router::class)->crud("$prefix/categories", CategorieCrudAction::class, 'blog.category.admin');
        }*/
    }
}
