<?php
    namespace App\Blog\Actions;

// use Psr\Http\Message\RequestInterface;
use App\Blog\Table\PostTable;
use Framework\Actions\RouterAwareAction;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Framework\Session\FlashService;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest as Request;

class AdminBlogAction
{

    private $renderer;
    private $pdo;
    private $router;
    private $flash;

    use RouterAwareAction;

    public function __construct(RendererInterface $renderer, Router $router, PostTable $postTable, FlashService $flash)
    {
        $this->postTable = $postTable;
           $this->router = $router;
         $this->renderer = $renderer;
         $this->flash = $flash;
    }
    /**
     * [__invoke description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function __invoke(Request $request)
    {
        if ($request->getMethod() === 'DELETE') {
            return $this->delete($request);
        }
        if (substr((string)$request->getUri(), -3) === 'new') {
            return $this->create($request);
        }
        if ($request->getAttribute('id')) {
            return $this->edit($request);
        }

        return $this->index($request);
    }
    /**
     * [index description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request): string
    {
        $params = $request->getQueryParams();
        $items = $this->postTable->findPagination(12, $params['p'] ?? 1);
        return $this->renderer->render('@blog/admin/index', compact('items'));
    }
    /**
     * [edit description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function edit(Request $request)
    {
        $item = $this->postTable->find($request->getAttribute('id'));
        if ($request->getMethod() === 'POST') {
            $params = $this->getParams($request);
            $params['update_at'] = date('Y-m-d H:i:s');
            $this->postTable->update($item->id, $params);
            $this->flash->success('L\'article a bien été modifié');

            return $this->redirect('blog.admin.index');
        }
        return $this->renderer->render('@blog/admin/edit', compact('item'));
    }
    /**
     * [create description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function create(Request $request)
    {
        if ($request->getMethod() === 'POST') {
            $params = $this->getParams($request);
            $params = array_merge($params, [
                'update_at' => date('Y-m-d H:i:s'),
                'create_at' => date('Y-m-d H:i:s')
            ]);
            $this->postTable->insert($params);
            $this->flash->success('L\'article a bien été ajouté');
            return $this->redirect('blog.admin.index');
        }
        return $this->renderer->render('@blog/admin/create', compact('item'));
    }

    /**
     * [delete description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function delete(Request $request)
    {
        $this->postTable->delete($request->getAttribute('id'));
        return $this->redirect('blog.admin.index');
    }
    /**
     * [getParams description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    private function getParams(Request $request)
    {
        return  array_filter($request->getParsedBody(), function ($key) {
            return in_array($key, ['name', 'slug', 'content']);
        }, ARRAY_FILTER_USE_KEY);
    }
}