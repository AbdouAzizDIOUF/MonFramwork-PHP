<?php
namespace Framework\Actions;

use GuzzleHttp\Psr7\MessageTrait;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;


trait RouterAwareAction
{
    /**
     * renvoie une reponse de redirectionnement
     * @param string $path
     * @param array $params
     * @return MessageTrait|ResponseInterface
     */
    public function redirect(string $path, array $params = [])
    {
         $redirectUri = $this->router->generateUri($path, $params);
            return (new Response())
                ->withStatus(301)
                ->withHeader('location', $redirectUri);
    }
}
