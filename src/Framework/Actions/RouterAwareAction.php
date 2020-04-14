<?php
namespace Framework\Actions;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

/**
 *
 */
trait RouterAwareAction
{
    /**
     * renvoie une reponse de redirection
     * @param  string $path   [description]
     * @param  array  $params [description]
     * @return [type]         [description]
     */
    public function redirect(string $path, array $params = []): ResponseInterface
    {
         $redirectUri = $this->router->generateUri($path, $params);
            return (new Response())
                ->withStatus(301)
                ->withHeader('location', $redirectUri);
    }
}
