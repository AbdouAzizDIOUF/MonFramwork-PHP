<?php
    namespace App\Blog\Actions;


use App\Blog\Table\PostTable;
use Framework\Actions\RouterAwareAction;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use GuzzleHttp\Psr7\MessageTrait;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest as Request;
use Psr\Http\Message\ResponseInterface;

class BlogAction
{
    use RouterAwareAction;

    private $renderer;
    private $router;
    private $postTable;

    public function __construct(RendererInterface $renderer, Router $router, PostTable $postTable)
    {
        $this->postTable = $postTable;
           $this->router = $router;
         $this->renderer = $renderer;
    }

    /**
     * @param Request $request
     * @return MessageTrait|ResponseInterface|string
     */
    public function __invoke(Request $request)
    {
        $id = $request->getAttribute('id');
        if ($id) {
            return $this->show($request);
        }
        return $this->index($request);
    }


    /**
     * la page d'acceuil
     * @param Request $request
     * @return MessageTrait|ResponseInterface|string
     */
    public function index(Request $request)
    {
        $val_param = 1;
        $params = $request->getQueryParams();
        if (isset($params['p']) && is_numeric($params['p'])){
            $val_param = ($params['p']<=1) ? 1 : $params['p'];
        }
        $posts = $this->postTable->findPagination(12, $val_param);

        return $this->renderer->render('@blog/index', compact('posts'));
    }

    /**
     * detail d'un article
     * @param Request $request
     * @return MessageTrait|ResponseInterface|string
     */
    public function show(Request $request)
    {
        $slug = $request->getAttribute('slug');
        $post = $this->postTable->find($request->getAttribute('id'));
        if ($post->slug !== $slug) {
            return $this->redirect('blog.show', [
                'slug' => $post->slug,
                'id' => $post->id
            ]);
        }
        
        return $this->renderer->render('@blog/show', ['post' => $post]);
    }
}
