<?php

use Mindy\Cart\Cart;
use Mindy\Cart\CartLine;
use Mindy\Cart\Interfaces\IDiscount;
use Mindy\Cart\Storage\MemoryStorage;
use Mindy\Cart\Storage\SessionStorage;
use Mindy\Cart\Interfaces\ICartItem;
use Mindy\Cart\Interfaces\ICartStock;

class Stock implements ICartStock
{
    public $count = 3;

    public function isAvailable(ICartItem $product, array $params = [], $quantity = 1)
    {
        return $this->count >= $quantity;
    }
}

class ExampleDiscount implements IDiscount
{
    /**
     * Apply discount to CartItem position. If new prices is equal old price - return old price.
     * @param \Mindy\Cart\Interfaces\ICartLine $item
     * @return int|float new price with discount
     */
    public function applyDiscount(ICartItem $product, $price, array $params = [], $quantity)
    {
        return [$price - 200, true];
    }
}

class Product implements ICartItem
{
    protected $data = [];

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * @return mixed unique product identification
     */
    public function getUniqueId()
    {
        return $this->data['id'];
    }

    public function toArray()
    {
        return $this->data;
    }

    /**
     * @param $data array
     * @return int|float
     */
    public function getPrice(array $data = [])
    {
        $price = $this->data['price'];
        foreach ($data as $key => $value) {
            if ($key == 'color') {
                switch ($value) {
                    case 'black':
                        $price += 50;
                        break;
                    case 'red':
                        $price += 20;
                        break;
                    default:
                        break;
                }
            }
        }
        return $price;
    }
}

/**
 * Created by PhpStorm.
 * User: max
 * Date: 19/07/16
 * Time: 12:30
 */
class CartTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Cart
     */
    protected $cart;

    protected function setUp()
    {
        $this->cart = new Cart([
            'storage' => new MemoryStorage()
        ]);
    }

    public function testInit()
    {
        $cart = new Cart();
        $this->assertTrue($cart->getStorage() instanceof SessionStorage);
        $cart = new Cart([
            'storage' => new MemoryStorage()
        ]);
        $this->assertTrue($cart->getStorage() instanceof MemoryStorage);
    }

    public function testSet()
    {
        $this->assertTrue($this->cart->getIsEmpty());
        $product = new Product([
            'id' => 1,
            'price' => 100
        ]);
        $key = $this->cart->makeKey($product);
        $this->cart->set($key, new CartLine([
            'product' => $product,
            'quantity' => 3,
        ]));
        $this->assertTrue($this->cart->has($key));
        $this->assertEquals(3, $this->cart->getQuantity());
        $this->assertFalse($this->cart->getIsEmpty());
    }

    public function testSetWithParams()
    {
        $this->assertTrue($this->cart->getIsEmpty());
        $params = [
            'color' => 3,
            'size' => 1
        ];
        $product = new Product([
            'id' => 1,
            'price' => 100
        ]);
        $key = $this->cart->makeKey($product, $params);
        $this->cart->set($key, new CartLine([
            'product' => $product,
            'params' => $params,
            'quantity' => 3
        ]));
        $this->assertTrue($this->cart->has($key));
        $this->assertEquals(3, $this->cart->getQuantity());
        $this->assertFalse($this->cart->getIsEmpty());
    }

    public function testClear()
    {
        $this->assertTrue($this->cart->getIsEmpty());
        $params = ['color' => 3, 'size' => 1];
        $product = new Product(['id' => 1, 'price' => 100]);
        $key = $this->cart->makeKey($product, $params);
        $this->cart->set($key, new CartLine([
            'product' => $product,
            'params' => $params,
            'quantity' => 3
        ]));
        $this->assertTrue($this->cart->has($key));
        $this->assertEquals(3, $this->cart->getQuantity());
        $this->assertFalse($this->cart->getIsEmpty());
        $this->cart->clear();
        $this->assertTrue($this->cart->getIsEmpty());
    }

    public function testGet()
    {
        $this->assertTrue($this->cart->getIsEmpty());

        $key1 = $this->cart->add(new Product(['id' => 1, 'price' => 100]), [], 1);
        $key2 = $this->cart->add(new Product(['id' => 2, 'price' => 100]), [], 2);

        $this->assertEquals(3, $this->cart->getQuantity());
        $this->assertFalse($this->cart->getIsEmpty());

        $product1 = $this->cart->get($key1);
        $line1 = $product1;
        $this->assertEquals(1, $line1->getQuantity());
        $this->assertEquals(100, $line1->getPrice());
        $this->assertEquals(1, $line1->getProduct()->getUniqueId());
        $product2 = $this->cart->get($key2);
        $line2 = $product2;
        $this->assertEquals(2, $line2->getQuantity());
        $this->assertEquals(200, $line2->getPrice());
        $this->assertEquals(2, $line2->getProduct()->getUniqueId());

        $this->cart->removeKey($key2);
        $this->assertEquals(1, $this->cart->getQuantity());
        $this->cart->has($key1);
    }

    public function testParams()
    {
        $product = new Product(['id' => 1, 'price' => 100]);

        $key = $this->cart->add($product, ['color' => 'black'], 1);
        $line = $this->cart->get($key);
        $this->assertEquals(150, $line->getPrice());

        $key = $this->cart->add($product, ['color' => 'red'], 1);
        $line = $this->cart->get($key);
        $this->assertEquals(120, $line->getPrice());

        $this->assertEquals(2, $this->cart->getQuantity());
        $this->assertEquals(270, $this->cart->getPrice());
    }

    public function testDiscount()
    {
        $cart = new Cart([
            'storage' => new MemoryStorage(),
            'discounts' => [
                function ($product, $price, $params, $quantity) {
                    if ($quantity > 100) {
                        return [$price * .8, false];
                    }
                    return [1, false];
                },
                new ExampleDiscount(),
            ]
        ]);
        $this->assertEquals(2, count($cart->getDiscounts()));
        $product = new Product(['id' => 1, 'price' => 100]);
        $key = $cart->add($product, [], 1000);
        // ExampleDiscount is not used
        $this->assertEquals(80000, $cart->getPrice());

        $cart = new Cart([
            'storage' => new MemoryStorage(),
            'discounts' => [
                function ($product, $price, $params, $quantity) {
                    return [$price * .8, true];
                },
                new ExampleDiscount(),
            ]
        ]);
        $this->assertEquals(2, count($cart->getDiscounts()));
        $product = new Product(['id' => 1, 'price' => 100]);
        $key = $cart->add($product, [], 1000);
        $this->assertEquals(79800, $cart->getPrice());
    }

    public function testSerialize()
    {
        $line = new CartLine([
            'product' => new Product,
            'params' => [],
            'quantity' => 1
        ]);
        $raw = $line->serialize();
        $data = unserialize($raw);
        $this->assertArrayHasKey('product', $data);
        $this->assertArrayHasKey('quantity', $data);
        $this->assertArrayHasKey('params', $data);

        $data['quantity'] = 2;
        $line->unserialize(serialize($data));
        $this->assertEquals(2, $line->getQuantity());
    }

    public function testQuantity()
    {
        $cart = new Cart([
            'storage' => new MemoryStorage(),
            'stock' => new Stock
        ]);
        $key = $cart->add(new Product(['id' => 1, 'price' => 100]), [], 1);
        $this->assertEquals(1, $cart->getQuantity());

        $cart->quantity($key, 2);
        $this->assertEquals(2, $cart->getQuantity());

        $state = $cart->quantity($key, 10);
        $this->assertFalse($state);

        $this->assertEquals(2, $cart->getQuantity());
    }

    public function testStock()
    {
        $cart = new Cart([
            'storage' => new MemoryStorage(),
            'stock' => new Stock
        ]);

        $cart->add(new Product(['id' => 1, 'price' => 100]), [], 1);
        $this->assertEquals(1, $cart->getQuantity());
        $cart->add(new Product(['id' => 1, 'price' => 100]), [], 1);
        $this->assertEquals(2, $cart->getQuantity());
        $cart->add(new Product(['id' => 1, 'price' => 100]), [], 1);
        $this->assertEquals(3, $cart->getQuantity());

        $key = $cart->add(new Product(['id' => 1, 'price' => 100]), [], 100);
        $this->assertFalse($key);
        $this->assertEquals(3, $cart->getQuantity());

        $setKey = $cart->makeKey(new Product(['id' => 2, 'price' => 200]));
        $line = new CartLine([
            'product' => new Product,
            'params' => [],
            'quantity' => 100
        ]);
        $state = $cart->set($setKey, $line);
        $this->assertFalse($state);

        $key = $cart->add(new Product(['id' => 1, 'price' => 100]), [], 100);
        $this->assertFalse($key);
        $this->assertEquals(3, $cart->getQuantity());
    }

    public function testApplyDiscount()
    {
        $cart = new Cart([
            'storage' => new MemoryStorage(),
            'discounts' => [
                function ($product, $price, $params, $quantity) {
                    return $price - 1;
                }
            ]
        ]);

        $product = new Product(['id' => 1, 'price' => 100]);
        $cart->applyDiscount($product, [], 1);
    }

    public function testFailedSet()
    {
        $key = $this->cart->add(new Product(['id' => 1, 'price' => 100]), [], 1);
        $state = $this->cart->quantity($key, 100);
        $this->assertEmpty($state);
    }

    public function testUpdate()
    {
        $cart = new Cart([
            'storage' => new MemoryStorage(),
        ]);
        $this->assertFalse($cart->update());

        $cart = new Cart([
            'storage' => new MemoryStorage(),
            'fetchCallback' => function ($product) {
                if ($product->getUniqueId() > 10) {
                    return $product;
                }

                return null;
            }
        ]);

        $key = $cart->add(new Product(['id' => 1, 'price' => 100]));
        $line = $cart->get($key);
        $this->assertTrue($line->getIsAvailable());

        $cart->add(new Product(['id' => 2, 'price' => 100]));
        $cart->add(new Product(['id' => 100, 'price' => 100]));
        $this->assertEquals(3, $cart->getQuantity());
        $cart->update();
        $this->assertEquals(3, $cart->getQuantity());
    }

    public function testCountInCart()
    {
        $this->cart->add(new Product(['id' => 1, 'price' => 100]), [], 1);
        $this->assertEquals(1, $this->cart->getQuantity());
        $this->cart->add(new Product(['id' => 1, 'price' => 100]), ['color' => 'black'], 1);
        $this->assertEquals(2, $this->cart->getQuantity());

        $this->assertEquals(1, $this->cart->countInCart(new Product(['id' => 1, 'price' => 100]), ['color' => 'black']));
    }

    public function testCartLineToArray()
    {
        $line = new CartLine([
            'product' => new Product(['id' => 1, 'price' => 100]),
            'params' => [],
            'quantity' => 2
        ]);
        $this->assertEquals([
            'product' => [
                'id' => 1,
                'price' => 100
            ],
            'params' => [],
            'quantity' => 2,
            'price' => 200
        ], $line->toArray());
    }
}