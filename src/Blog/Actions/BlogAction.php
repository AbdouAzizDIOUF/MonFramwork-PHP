<?php
    namespace App\Blog\Actions;

// use Psr\Http\Message\RequestInterface;
use App\Blog\Table\PostTable;
use Framework\Actions\RouterAwareAction;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest as Request;

class BlogAction
{

    private $renderer;
    private $pdo;
    private $router;
    use RouterAwareAction;

    public function __construct(RendererInterface $renderer, Router $router, PostTable $postTable)
    {
        $this->postTable = $postTable;
           $this->router = $router;
         $this->renderer = $renderer;
    }

    public function __invoke(Request $request)
    {
        $id = $request->getAttribute('id');
        if ($id) {
            return $this->show($request);
        }
        return $this->index($request);
    }

    public function index(Request $request): string
    {
        $params = $request->getQueryParams();
        $posts = $this->postTable->findPagination(12, $params['p'] ?? 1);
        return $this->renderer->render('@blog/index', compact('posts'));
    }

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
