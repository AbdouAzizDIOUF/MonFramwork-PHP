<?php
namespace App\Blog\Actions;

use App\Blog\Entity\Post;
use App\Blog\Table\CategorieTable;
use App\Blog\Table\PostTable;
use App\Framework\Actions\CrudAction;
use DateTime;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Framework\Session\FlashService;
use Framework\Validator;
use GuzzleHttp\Psr7\ServerRequest as Request;
use Psr\Http\Message\ServerRequestInterface;

class CategorieCrudAction extends CrudAction {

    protected $viewPath = '@blog/admin/categories';

    protected $routePrefix = 'blog.category.admin';

    public function __construct(RendererInterface $renderer, Router $router, CategorieTable $table, FlashService $flash)
    {
        parent::__construct($renderer, $router, $table, $flash);
    }

    protected function getParams(Request $request): array
    {
		return array_filter($request->getParsedBody(), static function ($key) {
			return in_array($key, ['name', 'slug']);
		}, ARRAY_FILTER_USE_KEY);
	}

	protected function getValidator(Request $request): Validator
    {
		return parent::getValidator($request)
			->required('name', 'slug')
            ->length('name', 2, 250)
			->length('slug', 2, 100)
			->slug('slug');
	}
}
