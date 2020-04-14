<?php
namespace Framework;

//use Framework\Router\Route; //use Zend\Expressive\Router\FastRouteRouter; //use Zend\Expressive\Router\Route as
//ZendRoute;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router\FastRouteRouter;
use Zend\Expressive\Router\Route;

class Router
{
    private $router;

    public function __construct()
    {
        $this->router = new FastRouteRouter();
    }

    /**
     *
     * @param string      $path
     * @param $callable
     * @param string|null $name
     */
    public function addRoute(string $path, $callable, ?string $name = null)
    {
        $this->router->addRoute(new Route($path, $callable, ['GET'], $name));
    }

    /**
     * [post description]
     * @param string $path
     * @param $callable
     * @param string|null $name
     * @return void
     */
    public function post(string $path, $callable, ?string $name = null)
    {
        $this->router->addRoute(new Route($path, $callable, ['POST'], $name));
    }

    /**
     *
     * @param string $path
     * @param $callable
     * @param string|null $name
     * @return void
     */
    public function delete(string $path, $callable, ?string $name = null)
    {
        $this->router->addRoute(new Route($path, $callable, ['DELETE'], $name));
    }

    /**
     *  @param ServerRequestInterface $request
     *  @return Route | null
     */
    public function match(ServerRequestInterface $request): ?Router\Route
    {
        $result = $this->router->match($request);
        if (!$result->isSuccess()) {
            return null;
        }
            return new Router\Route(
                $result->getMatchedRouteName(),
                $result->getMatchedMiddleware(),
                $result->getMatchedParams()
            );
    }

    public function crud(string $prefixPath, $callable, string $prefixName)
    {
        $this->addRoute((string)$prefixPath, $callable, "$prefixName.index");
        $this->addRoute("$prefixPath/new", $callable, "$prefixName.create");
        $this->post("$prefixPath/new", $callable);
        $this->addRoute("$prefixPath/{id:\d+}", $callable, "$prefixName.edit");
        $this->post("$prefixPath/{id:\d+}", $callable);
        $this->delete("$prefixPath/{id:\d+}", $callable, "$prefixName.delete");
    }

    /**
     *
     * @param string $name
     * @param array $params
     * @param array $queryParams
     * @return string|null
     */
    public function generateUri(string $name, array $params = [], array $queryParams = []): ?string
    {
        $uri = $this->router->generateUri($name, $params);
        if ($queryParams !== null) {
            return $uri . '?' . http_build_query($queryParams);
        }
        return $uri;
    }
}
