<?php

abstract class Page
{
    public function __construct(protected Renderer $renderer)
    {
    }

    public function changeRenderer(Renderer $renderer): void
    {
        $this->renderer = $renderer;
    }

    abstract public function view(): string;
}

class SimplePage extends Page
{
    public function __construct(Renderer $renderer, protected string $title, protected string $content)
    {
        parent::__construct($renderer);
    }

    public function view(): string
    {
        return $this->renderer->renderParts([
            $this->renderer->renderHeader(),
            $this->renderer->renderTitle($this->title),
            $this->renderer->renderTextBlock($this->content),
            $this->renderer->renderFooter()
        ]);
    }
}

class ProductPage extends Page
{
    public function __construct(Renderer $renderer, protected Product $product)
    {
        parent::__construct($renderer);
    }

    public function view(): string
    {
        return $this->renderer->renderParts([
            $this->renderer->renderHeader(),
            $this->renderer->renderTitle($this->product->getTitle()),
            $this->renderer->renderTextBlock($this->product->getDescription()),
            $this->renderer->renderImage($this->product->getImage()),
            $this->renderer->renderLink('/cart/add/' . $this->product->getId(), 'Add to cart'),
            $this->renderer->renderFooter()
        ]);
    }
}

class Product
{
    public function __construct(
        private string $id,
        private string $title,
        private string $description,
        private string $image,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getImage(): string
    {
        return $this->image;
    }
}

interface Renderer
{
    public function renderTitle(string $title): string;

    public function renderTextBlock(string $text): string;

    public function renderImage(string $url): string;

    public function renderLink(string $url, string $title): string;

    public function renderHeader(): string;

    public function renderFooter(): string;

    public function renderParts(array $parts): string;
}

class HTMLRenderer implements Renderer
{
    public function renderTitle(string $title): string
    {
        return "<h1>$title</h1>";
    }

    public function renderTextBlock(string $text): string
    {
        return "<div class='text'>$text</div>";
    }

    public function renderImage(string $url): string
    {
        return "<img src='$url'>";
    }

    public function renderLink(string $url, string $title): string
    {
        return "<a href='$url'>$title</a>";
    }

    public function renderHeader(): string
    {
        return '<html><body>';
    }

    public function renderFooter(): string
    {
        return '</body></html>';
    }

    public function renderParts(array $parts): string
    {
        return implode(PHP_EOL, $parts);
    }
}

class JsonRenderer implements Renderer
{
    public function renderTitle(string $title): string
    {
        return '\'title\': \'' . $title . '\'';
    }

    public function renderTextBlock(string $text): string
    {
        return '\'text\': \'' . $text . '\'';
    }

    public function renderImage(string $url): string
    {
        return '\'img\': \'' . $url . '\'';
    }

    public function renderLink(string $url, string $title): string
    {
        return '\'link\': {\'href\': \'' . $url . '\', \'title\': \'' . $title . '\'}';
    }

    public function renderHeader(): string
    {
        return '';
    }

    public function renderFooter(): string
    {
        return '';
    }

    public function renderParts(array $parts): string
    {
        return '{' . PHP_EOL . implode(',' . PHP_EOL, array_filter($parts)) . PHP_EOL . '}';
    }
}

function client(Page $page)
{
    echo $page->view();
}

$HTMLRenderer = new HTMLRenderer();
$JSONRenderer = new JsonRenderer();

$page = new SimplePage($HTMLRenderer, 'Home', 'Welcome to our website!');
echo 'HTML:' . PHP_EOL;
client($page);
echo PHP_EOL;

$page->changeRenderer($JSONRenderer);
echo 'JSON:' . PHP_EOL;
client($page);
echo PHP_EOL;

$product = new Product(
    '123',
    'Star Wars, episode1',
    'A long time ago in a galaxy far, far away...',
    '/images/star-wars.jpeg'
);

$page = new ProductPage($HTMLRenderer, $product);
echo 'HTML:' . PHP_EOL;
client($page);
echo PHP_EOL;

$page->changeRenderer($JSONRenderer);
echo 'JSON:' . PHP_EOL;
client($page);