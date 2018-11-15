<?php
namespace Framework\Twig;

use Framework\Router;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap4View;

class PagerFantaExtension extends \Twig_Extension
{

    private $router;
    /**
     * [__construct description]
     * @param Router $router [description]
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }
    /**
     * [getFunctions description]
     * @return [type] [description]
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('paginate', [$this, 'paginate'], ['is_safe' => ['html']])
        ];
    }
    /**
     * [paginate description]
     * @param  Pagerfanta $paginatedResults [description]
     * @param  string     $route            [description]
     * @param  array      $queryArgs        [description]
     * @return [type]                       [description]
     */
    public function paginate(Pagerfanta $paginatedResults, string $route, array $queryArgs = []): string
    {
        $view = new TwitterBootstrap4View();
        return $view->render($paginatedResults, function ($page) use ($route, $queryArgs) {
            if ($page > 1) {
                $queryArgs['p'] = $page;
            }
            return $this->router->generateUri($route, [], $queryArgs);
        });
    }
}
