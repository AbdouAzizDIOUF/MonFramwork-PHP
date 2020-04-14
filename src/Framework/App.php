<?php
namespace Framework;

use GuzzleHttp\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class App {

	private $modules = [];
	private $container;

	public function __construct(ContainerInterface $container, array $modules = []) {
		$this->container = $container;
		foreach ($modules as $module) {
			$this->modules[] = $container->get($module);
		}
	}

	/**
	 * [run description]
	 * @param  ServerRequestInterface $request
	 * @return ResponseInterface
	 */
	public function run(ServerRequestInterface $request): ResponseInterface{
		$uri = $request->getUri()->getPath();
		$parsedBody = $request->getParsedBody();
		if (array_key_exists('_method', $parsedBody) && in_array($parsedBody['_method'], ['DELETE', 'PUT'])) {
			$request = $request->withMethod($parsedBody['_method']);
		}
		if (!empty($uri) && $uri[-1] === "/") {
			return (new Response())
				->withStatus(301)
				->withHeader('Location', substr($uri, 0, -1));
		}
		$router = $this->container->get(Router::class);
		$route = $router->match($request);

		if (is_null($route)) {
			return new Response(404, [], '<h1>Erreur 404</h1>');
		}
		$params = $route->getParams();
		//array_reduce() applique itérativement la fonction callback aux éléments du tableau array, de manière à réduire
		//le tableau à une valeur simple
		$request = array_reduce(array_keys($params), function ($request, $key) use ($params) {
			return $request->withAttribute($key, $params[$key]);
		}, $request);
		$callback = $route->getCallback();
		if (is_string($callback)) {
			$callback = $this->container->get($callback);
		}
		$response = call_user_func_array($callback, [$request]);
		if (is_string($response)) {
			return new Response(200, [], $response);
		} elseif ($response instanceof ResponseInterface) {
			return $response;
		} else {
			throw new \Exception("the respose is not a string or instance of ResponseInterface");
		}
	}

	public function getContainer(): ContainerInterface {
		return $this->container;
	}
}
