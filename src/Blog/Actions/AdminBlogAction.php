<?php
namespace App\Blog\Actions;

use App\Blog\Entity\Post;
use App\Blog\Table\PostTable;
use DateTime;
use Framework\Actions\RouterAwareAction;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Framework\Session\FlashService;
use Framework\Validator;
use GuzzleHttp\Psr7\ServerRequest as Request;
use Psr\Http\Message\ResponseInterface;

class AdminBlogAction {

    use RouterAwareAction;

    private $renderer;
	private $pdo;
	private $router;
	private $flash;
    private $postTable;

	public function __construct(RendererInterface $renderer, Router $router, PostTable $postTable, FlashService $flash) {
		$this->postTable = $postTable;
		$this->router = $router; // utilise au niveau de RouterAwareAction
		$this->renderer = $renderer;
		$this->flash = $flash;
	}

    /**
     *
     * @param Request $request
     * @return ResponseInterface|string
     */
	public function __invoke(Request $request) {
		if ($request->getMethod() === 'DELETE') {
			return $this->delete($request);
		}
		if (substr((string) $request->getUri(), -3) === 'new') {
			return $this->create($request);
		}
		if ($request->getAttribute('id')) {
			return $this->edit($request);
		}

		return $this->index($request);
	}

    /**
     * Page d'acceuil de l'administration des articles
     * @param Request $request
     * @return string
     */
	public function index(Request $request): string{
		$params = $request->getQueryParams();
		$items = $this->postTable->findPagination(12, $params['p'] ?? 1);

		return $this->renderer->render('@blog/admin/index', compact('items'));
	}

    /**
     *  edition d'un article
     * @param Request $request
     * @return ResponseInterface|string
     */
	public function edit(Request $request) {
		$item = $this->postTable->find($request->getAttribute('id'));
		if ($request->getMethod() === 'POST') {
			$params = $this->getParams($request);
			$validator = $this->getValidator($request);
			if ($validator->isValid()) {
				$this->postTable->update($item->id, $params);
				$this->flash->success('L\'article a bien été modifié');

				return $this->redirect('blog.admin.index');
			}
			$errors = $validator->getErrors();
			$params['id'] = $item->id;
			$item = $params;

            return $this->renderer->render('@blog/admin/edit', compact('item', 'errors'));
		}

		return $this->renderer->render('@blog/admin/edit', compact('item'));
	}

    /**
     * Creer un article
     * @param Request $request
     * @return ResponseInterface|string
     */
	public function create(Request $request) {
		if ($request->getMethod() === 'POST') {
			$params = $this->getParams($request);
			$validator = $this->getValidator($request);
			if ($validator->isValid()) {
				$this->postTable->insert($params);
				$this->flash->success('L\'article a bien été ajouté');

				return $this->redirect('blog.admin.index');
			}
			$errors = $validator->getErrors();
			$item = $params;

            return $this->renderer->render('@blog/admin/create', compact('item', 'errors'));
        }
		$item = new Post();
		$item->create_at = new DateTime();

		return $this->renderer->render('@blog/admin/create', compact('item'));
	}

    /**
     * La suppression d'un article
     * @param Request $request
     * @return ResponseInterface
     */
	public function delete(Request $request): ResponseInterface
    {
		$this->postTable->delete($request->getAttribute('id'));

		return $this->redirect('blog.admin.index');
	}


	private function getParams(Request $request): array
    {
		$params = array_filter($request->getParsedBody(), static function ($key) {
			return in_array($key, ['name', 'slug', 'content', 'create_at']);
		}, ARRAY_FILTER_USE_KEY);
		return array_merge($params, [
			'update_at' => date('Y-m-d H:i:s'),
		]);
	}

	private function getValidator(Request $request): Validator
    {
		return (new Validator($request->getParsedBody()))
			->required('content', 'name', 'slug', 'create_at')
			->length('content', 10)
			->length('name', 2, 250)
			->length('slug', 2, 50)
			->dateTime('create_at')
			->slug('slug');
	}
}
