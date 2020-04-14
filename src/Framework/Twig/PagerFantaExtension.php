<?php
namespace Framework\Twig;

use Framework\Router;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap4View;
use Twig_Extension;
use Twig_SimpleFunction;

class PagerFantaExtension extends Twig_Extension
{

    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     *
     * @return Twig_SimpleFunction[]
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('paginate', [$this, 'paginate'], ['is_safe' => ['html']])
        ];
    }

    /**
     *
     * @param Pagerfanta $paginatedResults
     * @param string $route
     * @param array $queryArgs
     * @return string
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
