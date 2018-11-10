<?php

use Psr\Container\ContainerInterface;
use function DI\factory;
use function DI\get;
use function DI\object;

    return
    [
            'database.host' => 'localhost',
        'database.username' => 'root',
        'database.password' => 'root',
            'database.name' => 'monsupersite',
               'views.path' => dirname(__DIR__).'/views',
          'twig.extensions' => [
                get(\Framework\Router\RouterTwigExtension::class),
                get(\Framework\Twig\PagerFantaExtension::class),
                get(\Framework\Twig\TestExtension::class),
                get(\Framework\Twig\TimeExtension::class)
            ],
            \Framework\Router::class => object(),
            \Framework\Renderer\RendererInterface::class => factory(\Framework\Renderer\TwigRendererFactory::class),
            \PDO::class => function (ContainerInterface $c) {
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
