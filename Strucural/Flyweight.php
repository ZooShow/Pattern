<?php

declare(strict_types=1);

class ShoppingCart
{
    /**
     * @var Product[]
     */
    private array $products = [];

    public function addProduct(string $title, int $price, string $brandName): void
    {
        $this->products[] = ProductFactory::getProduct($title, $price, $brandName);
    }
}

class Product
{
    private string $title;
    private int $price;
    private ProductBrand $brand;

    public function __construct(string $title, int $price, ProductBrand $brand)
    {
        $this->title = $title;
        $this->price = $price;
        $this->brand = $brand;
    }
}

class ProductBrand
{
    private string $brandName;

    public function __construct(string $brandName)
    {
        $this->brandName = $brandName;
    }
}

class ProductFactory
{
    private static array $brandTypes = [];

    public static function getProduct(string $title, int $price, string $brandName): Product
    {
        $brand = static::getBrand($brandName);

        return new Product($title, $price, $brand);
    }

    private static function getBrand(string $brandName): ProductBrand
    {
        if (isset(static::$brandTypes[$brandName])) {
            return static::$brandTypes[$brandName];
        }

        $brand = new ProductBrand($brandName);
        static::$brandTypes[$brandName] = $brand;

        return $brand;
    }
}

$shoppingCart = new ShoppingCart();
$shoppingCart->addProduct('Кроссовки', 120, 'Nike');
$shoppingCart->addProduct('Сандали', 100, 'Nike');
$shoppingCart->addProduct('Туфли', 110, 'Nike');
$shoppingCart->addProduct('Спортивные штаны', 140, 'Asics');
$shoppingCart->addProduct('Футбольный мяч', 90, 'Adidas');
