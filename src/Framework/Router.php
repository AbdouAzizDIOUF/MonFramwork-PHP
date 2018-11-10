<?php
namespace Framework;

//use Framework\Router\Route; //use Zend\Expressive\Router\FastRouteRouter; //use Zend\Expressive\Router\Route as
//ZendRoute;
use Psr\Http\Message\ServerRequestInterface;

class Router
{
    private $router;

    public function __construct()
    {
        $this->router = new \Zend\Expressive\Router\FastRouteRouter();
    }

    public function addRoute(string $path, $callable, string $name)
    {
        $this->router->addRoute(new \Zend\Expressive\Router\Route($path, $callable, ['GET'], $name));
    }

    /**
     *  @param ServerRequestInterface $request
     *  @return Route | null
     */
    public function match(ServerRequestInterface $request): ?\Framework\Router\Route
    {
        $result = $this->router->match($request);
        if (!$result->isSuccess()) {
            return null;
        }
            return new \Framework\Router\Route(
                $result->getMatchedRouteName(),
                $result->getMatchedMiddleware(),
                $result->getMatchedParams()
            );
    }

    public function generateUri(string $name, array $params = [], array $queryParams = []): ?string
    {
        $uri = $this->router->generateUri($name, $params);
        if (!empty($queryParams)) {
            return $uri . '?' . http_build_query($queryParams);
        }
        return $uri;
    }
}
