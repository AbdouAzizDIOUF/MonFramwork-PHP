<?php
    namespace App\Blog\Entity;

class Post
{
    public $id;
    public $name;
    public $slug;
    public $content;
    public $creat_at;
    public $updat_at;

    public function __construct()
    {
        if ($this->create_at) {
            $this->create_at = new \DateTime($this->create_at);
        }
        if ($this->update_at) {
            $this->update_at = new \DateTime($this->update_at);
        }
    }
}
