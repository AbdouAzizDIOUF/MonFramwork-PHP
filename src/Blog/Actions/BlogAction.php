<?php
    namespace App\Blog\Actions;

    use Framework\Renderer\ResponseInterface;
    use GuzzleHttp\Psr7\ServerRequest as Request;
    use Framework\Renderer\RendererInterface;

class BlogAction
{

    private $renderer;

    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function __invoke(Request $request)
    {
        $slug = $request->getAttribute('slug');
        if ($slug) {
            return $this->show($slug);
        }
        return $this->index();
    }

    public function index(): string
    {
        return $this->renderer->render('@blog/index');
    }

    public function show(string $slug): string
    {
        return $this->renderer->render('@blog/show', ['slug' => $slug]);
    }
}
