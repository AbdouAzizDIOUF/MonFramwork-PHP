<?php

use App\Admin\AdminModule;
use App\Blog\BlogModule;
use DI\ContainerBuilder;
use Framework\App;
use function Http\Response\send;

require dirname(__DIR__) . '/vendor/autoload.php';

$builder = new ContainerBuilder();
$builder->addDefinitions(dirname(__DIR__) . '/config/config.php');

$modules = [AdminModule::class, BlogModule::class];

foreach ($modules as $module) {
	if ($module::DEFINITIONS) {
		$builder->addDefinitions($module::DEFINITIONS);
	}
}

$container = $builder->build();

$app = new App($container, $modules);

if (PHP_SAPI !== 'cli') {
    try {
        $response = $app->run(GuzzleHttp\Psr7\ServerRequest::fromGlobals());
        send($response);
    } catch (Exception $e) {
    }
}
