<?php
namespace Framework\Twig;

use Framework\Router;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap4View;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PagerFantaExtension extends AbstractExtension
{

    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     *
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('paginate', [$this, 'paginate'], ['is_safe' => ['html']])
        ];
    }

    /**
     *
     * @param Pagerfanta $pagerfanta
     * @param string $route
     * @param array $queryArgs
     * @return string
     */
    public function paginate(Pagerfanta $pagerfanta, string $route, array $queryArgs = []): string
    {
        $view = new TwitterBootstrap4View();
        return $view->render($pagerfanta, function ($page) use ($route, $queryArgs) {
            if ($page > 1) {
                $queryArgs['p'] = $page;
            }

            return $this->router->generateUri($route, [], $queryArgs);
        });
    }
}
