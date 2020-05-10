<?php
namespace App\Admin;

use App\Blog\Actions\CategorieCrudAction;
use App\Blog\Actions\PostCrudAction;
use Framework\Module;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Container\ContainerInterface;

class AdminModule extends Module
{
    public const DEFINITIONS = __DIR__ . '/config.php';

    /**
     * Chemin de la vue de l'administration
     * AdminModule constructor.
     * @param RendererInterface $renderer
     * @param ContainerInterface $container
     */
    public function __construct(RendererInterface $renderer, ContainerInterface $container)
    {
        $renderer->addPath('admin', __DIR__ . '/views');
        $prefix = $container->get('admin.prefix');
        $container->get(Router::class)->crud("$prefix/posts", PostCrudAction::class, 'blog.admin');
        $container->get(Router::class)->crud("$prefix/categories", CategorieCrudAction::class, 'blog.category.admin');
    }
}
