<?= $renderer->render('header') ?>

<h1>Bien venue sur le blog</h1>
<ul>
    <li><a href="<?= $router->generateUri('blog.show', ['slug' => 'zrrfrf-7sdf']); ?>"> Article1</a></li>
    <li>Article1</li>
    <li>Article1</li>
    <li>Article1</li>
</ul>

<?= $renderer->render('footer') ?>
