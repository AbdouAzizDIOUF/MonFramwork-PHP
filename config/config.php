<?php

use Framework\Router\RouterTwigExtension;
use Framework\Session\PHPSession;
use Framework\Session\SessionInterface;
use Framework\Twig\FlashExtension;
use Framework\Twig\FormExtension;
use Framework\Twig\PagerFantaExtension;
use Framework\Twig\TestExtension;
use Framework\Twig\TimeExtension;
use Psr\Container\ContainerInterface;
use function DI\factory;
use function DI\get;
use function DI\object;

    return
    [
            'database.host' => 'localhost',
        'database.username' => 'root',
        'database.password' => 'root',
            'database.name' => 'php_monsupersite',
               'views.path' => dirname(__DIR__).'/views',
          'twig.extensions' => [
                get(RouterTwigExtension::class),
                get(PagerFantaExtension::class),
                get(TestExtension::class),
                get(TimeExtension::class),
                get(FlashExtension::class),
                get(FormExtension::class)
            ],
            SessionInterface::class => object(PHPSession::class),
            \Framework\Router::class => object(),
            \Framework\Renderer\RendererInterface::class => factory(\Framework\Renderer\TwigRendererFactory::class),
            PDO::class => static function (ContainerInterface $c) {
                return new PDO(
                    'mysql:host=' .$c->get('database.host') . ';dbname=' .$c->get('database.name'),
                    $c->get('database.username'),
                    $c->get('database.password'),
                    [
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                    ]
                );
            }
    ];
