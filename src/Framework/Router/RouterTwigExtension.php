<?php
namespace Framework\Router;

use Framework\Router;

class RouterTwigExtension extends \Twig_Extension
{

    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getFunctions()
    {
        return
        [
            new \Twig_SimpleFunction('path', [$this, 'pathFor'])
        ];
    }

    /**
     * [pathFor description]
     * @param  string $path   [description]
     * @param  array  $params [description]
     * @return [type]         [description]
     */
    public function pathFor(string $path, array $params = []): string
    {
        return $this->router->generateUri($path, $params);
    }
}
