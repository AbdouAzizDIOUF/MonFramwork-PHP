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

    /**
     * [addRoute description]
     * @param string      $path     [description]
     * @param [type]      $callable [description]
     * @param string|null $name     [description]
     */
    public function addRoute(string $path, $callable, ?string $name = null)
    {
        $this->router->addRoute(new \Zend\Expressive\Router\Route($path, $callable, ['GET'], $name));
    }

    /**
     * [post description]
     * @param  string      $path     [description]
     * @param  [type]      $callable [description]
     * @param  string|null $name     [description]
     * @return [type]                [description]
     */
    public function post(string $path, $callable, ?string $name = null)
    {
        $this->router->addRoute(new \Zend\Expressive\Router\Route($path, $callable, ['POST'], $name));
    }

    /**
     * [delete description]
     * @param  string      $path     [description]
     * @param  [type]      $callable [description]
     * @param  string|null $name     [description]
     * @return [type]                [description]
     */
    public function delete(string $path, $callable, ?string $name = null)
    {
        $this->router->addRoute(new \Zend\Expressive\Router\Route($path, $callable, ['DELETE'], $name));
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

    public function crud(string $prefixPath, $callable, string $prefixName)
    {
        $this->addRoute("$prefixPath", $callable, "$prefixName.index");
        $this->addRoute("$prefixPath/new", $callable, "$prefixName.create");
        $this->post("$prefixPath/new", $callable);
        $this->addRoute("$prefixPath/{id:\d+}", $callable, "$prefixName.edit");
        $this->post("$prefixPath/{id:\d+}", $callable);
        $this->delete("$prefixPath/{id:\d+}", $callable, "$prefixName.delete");
    }

    /**
     * [generateUri description]
     * @param  string $name        [description]
     * @param  array  $params      [description]
     * @param  array  $queryParams [description]
     * @return [type]              [description]
     */
    public function generateUri(string $name, array $params = [], array $queryParams = []): ?string
    {
        $uri = $this->router->generateUri($name, $params);
        if (!is_null($queryParams)) {
            return $uri . '?' . http_build_query($queryParams);
        }
        return $uri;
    }
}
