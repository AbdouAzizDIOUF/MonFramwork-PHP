<?= $renderer->render('header') ?>

<h1 style=" padding-left: 50px;"> Bien venue sur le blog</h1>
<ul style="padding-left: 52px;">
    <li><a href="<?= $router->generateUri('blog.show', ['slug' => 'zrrfrf-7sdf']); ?>"> Article1</a></li>
    <li>Article1</li>
    <li>Article1</li>
    <li>Article1</li>
</ul>

<?= $renderer->render('footer') ?>
