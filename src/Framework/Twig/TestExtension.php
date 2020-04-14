<?php
namespace Framework\Twig;

use Twig_Extension;
use Twig_SimpleFilter;

class TestExtension extends Twig_Extension
{
    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('excerpt', [$this, 'excerpt'])
        ];
    }

    public function excerpt(string $content, int $maxlength = 100): string
    {
        if (mb_strlen($content) > $maxlength) {
            $excerpt = mb_substr($content, 0, $maxlength);
            $lastSpace = mb_strrpos($excerpt, ' ');
            return mb_substr($excerpt, 0, $lastSpace) . '...';
        }
        return $content;
    }
}
