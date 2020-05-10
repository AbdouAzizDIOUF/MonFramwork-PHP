<?php


namespace App\Framework\Actions;


use App\Framework\Database\ITable;
use Framework\Actions\RouterAwareAction;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Framework\Session\FlashService;
use Framework\Validator;
use GuzzleHttp\Psr7\ServerRequest as Request;
use Psr\Http\Message\ResponseInterface;

class CrudAction
{
    use RouterAwareAction;

    private $renderer;
    private $pdo;
    private $router;
    private $flash;

    /**
     * @var ITable
     */
    protected $table;
    /**
     * @var string
     */
    protected $viewPath;

    /**
     * @var string
     */
    protected $routePrefix;

    /**
     * @var string[]
     */
    protected $message=[
        'create' => "l'element a bien ete creer",
        'editer' => "l'element a bien ete modifie"
    ];
    public function __construct(RendererInterface $renderer, Router $router, ITable $table, FlashService $flash) {
        $this->table = $table;
        $this->router = $router; // utilise au niveau de RouterAwareAction
        $this->renderer = $renderer;
        $this->flash = $flash;
    }

    /**
     *  methode magique invoque a l'appelle de la classe
     * @param Request $request
     * @return ResponseInterface|string
     */
    public function __invoke(Request $request)
    {
        $this->renderer->addGlobal('viewPath', $this->viewPath);
        $this->renderer->addGlobal('routePrefix', $this->routePrefix);
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
        $val_param = 1;
        $params = $request->getQueryParams();
        if (isset($params['p']) && is_numeric($params['p'])){
            $val_param = ($params['p']<=1) ? 1 : $params['p'];
        }

        $items = $this->table->findPagination(12, $val_param);

        return $this->renderer->render($this->viewPath.'/index', compact('items'));
    }

    /**
     *  edition d'un article
     * @param Request $request
     * @return ResponseInterface|string
     */
    public function edit(Request $request) {
        $item = $this->table->find($request->getAttribute('id'));
        if ($request->getMethod() === 'POST') {
            $params = $this->getParams($request);
            $validator = $this->getValidator($request);
            if ($validator->isValid()) {
                $this->table->update($item->id, $params);
                $this->flash->success($this->message['editer']);

                return $this->redirect($this->routePrefix.'.index');
            }
            $errors = $validator->getErrors();
            $params['id'] = $item->id;
            $item = $params;

            return $this->renderer->render(
                $this->viewPath.'/edit',
                $this->formParams(compact('item', 'errors'))
            );
        }

        return $this->renderer->render(
            $this->viewPath.'/edit',
            $this->formParams(compact('item'))
        );
    }

    /**
     * Creer un article
     * @param Request $request
     * @return ResponseInterface|string
     */
    public function create(Request $request)
    {
        $item = $this->getNewEntity();
        if ($request->getMethod() === 'POST') {
            $params = $this->getParams($request);
            $validator = $this->getValidator($request);
            if ($validator->isValid()) {
                $this->table->insert($params);
                $this->flash->success($this->message['create']);

                return $this->redirect($this->routePrefix.'.index');
            }
            $errors = $validator->getErrors();
            $item = $params;

            return $this->renderer->render(
                $this->viewPath.'/create',
                $this->formParams(compact('item', 'errors'))
            );
        }

        return $this->renderer->render($this->viewPath.'/create',
            $this->formParams(compact('item'))
        );
    }

    /**
     * La suppression d'un article
     * @param Request $request
     * @return ResponseInterface
     */
    public function delete(Request $request): ResponseInterface
    {
        $this->table->delete($request->getAttribute('id'));

        return $this->redirect($this->routePrefix.'.index');
    }


    protected function getParams(Request $request): array
    {
        return array_filter($request->getParsedBody(), static function ($key) {
            return in_array($key, [], true);
        }, ARRAY_FILTER_USE_KEY);
    }

    protected function getValidator(Request $request): Validator
    {
        return (new Validator($request->getParsedBody()));
    }

    /**
     * @return mixed
     */
    protected function getNewEntity()
    {
        return [];
    }

    /**
     * permet de traiter les paramettre a envoyer a la vue
     *
     * @param array $params
     * @return array
     */
    protected function formParams(array $params): array
    {
        return $params;
    }

}