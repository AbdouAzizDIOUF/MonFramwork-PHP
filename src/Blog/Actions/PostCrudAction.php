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

class PostCrudAction extends CrudAction {

    protected $viewPath = '@blog/admin/posts';

    protected $routePrefix = 'blog.admin';
    /**
     * @var CategorieTable
     */
    private $categorieTable;

    public function __construct(RendererInterface $renderer, Router $router, PostTable $table, FlashService $flash, CategorieTable $categorieTable)
    {
        parent::__construct($renderer, $router, $table, $flash);
        $this->categorieTable = $categorieTable;
    }

    protected function formParams(array $params): array
    {
        $params['categories'] = $this->categorieTable->findList();
        return $params;
    }
    protected function getNewEntity(): Post
    {
        $post = new Post();
        $post->create_at = new DateTime();

        return $post;
    }

    protected function getParams(Request $request): array
    {
		$params = array_filter($request->getParsedBody(), static function ($key) {
			return in_array($key, ['name', 'slug', 'content', 'create_at', 'category_id']);
		}, ARRAY_FILTER_USE_KEY);
		return array_merge($params, [
			'update_at' => date('Y-m-d H:i:s'),
		]);
	}

	protected function getValidator(Request $request): Validator
    {
		return parent::getValidator($request)
			->required('content', 'name', 'slug', 'create_at','category_id')
			->length('content', 10)
			->length('name', 2, 250)
			->length('slug', 2, 100)
            ->exists('category_id', $this->categorieTable->getTable(), $this->categorieTable->getPdo())
			->dateTime('create_at')
			->slug('slug');
	}
}
