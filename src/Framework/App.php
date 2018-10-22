<?php
namespace Framework;

//use Psr\Http\Message\ResponseInterface;
//use Psr\Http\Message\ServerRequestInterface;
//use GuzzleHttp\Psr7\Response;
 use GuzzleHttp\Psr7\ServerRequest;

class App
{
    /**
         * list of modules
         *  @var array
         */
    private $modules = [];

    /**
         *  @var Router
         */
    private $router;

    /**
         *App consructeur
         *@param string[] $modules liste des modules a charger
         */
    public function __construct(array $modules = [], array $dependences = [])
    {
        $this->router = new \Framework\Router();
        if (array_key_exists('renderer', $dependences)) {
            $dependences['renderer']->addGlobal('router', $this->router);
        }
        foreach ($modules as $module) {
            $this->modules[] = new $module($this->router, $dependences['renderer']);
        }
    }

    public function run(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        $uri = $request->getUri()->getPath();

        if (!empty($uri) && $uri[-1] === "/") {
            return (new \GuzzleHttp\Psr7\Response())
            ->withStatus(301)
            ->withHeader('Location', substr($uri, 0, -1));
        }
        $route = $this->router->match($request);

        if (is_null($route)) {
            return new \GuzzleHttp\Psr7\Response(404, [], '<h1>Erreur 404</h1>');
        }
        $params = $route->getParams();
        /*array_reduce() applique itérativement la fonction callback aux éléments du tableau array, de manière à réduire le tableau à une valeur simple.*/
        $request = array_reduce(array_keys($params), function ($request, $key) use ($params) {
            return $request->withAttribute($key, $params[$key]);
        }, $request);

        $response = call_user_func_array($route->getCallback(), [$request]);

        if (is_string($response)) {
            return new \GuzzleHttp\Psr7\Response(200, [], $response);
        } elseif ($response instanceof \Psr\Http\Message\ResponseInterface) {
            return $response;
        } else {
            throw new \Exception("the respose is not a string or instance of ResponseInterface");
        }
    }
}
