<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\Order2Repository;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Entity\Order2;



class CommandController extends AbstractController
{
    #[Route('/command', name: 'app_command')]
    public function index(ProductRepository $productRepository): Response
    {
        $list = array();
        $products = $productRepository->findAll();

        return $this->render('command/index.html.twig', [
            'controller_name' => 'CommandController',
            'products' => $products,
            'list' => $list,
        ]);
    }

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    #[Route('/add-to-cart', name: 'app_add_to_cart', methods: ['POST'])]
    public function addToCart(Request $request, ProductRepository $productRepository): Response
    {
        $cart = $request->getSession()->get('cart', []);

        foreach ($productRepository->findAll() as $product) {
            $productId = $request->request->get('product_id_' . $product->getId());
            $quantity = $request->request->get('quantity_' . $product->getId());

            if ($productId !== null && $quantity !== null) {
                $cart[$productId] = $quantity;
            }
        }

        $request->getSession()->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/create-order', name: 'app_create_order', methods: ['POST'])]
    public function createOrder(Request $request, ProductRepository $productRepository, EntityManagerInterface $entityManager): Response
    {
        $formData = $request->request->all();

        $order = [];

        foreach ($formData as $key => $value) {
            if (strpos($key, 'product_id_') === 0 && $value > 0) {
                $productId = substr($key, 11);
                $quantity = $request->request->get('quantity_' . $productId);
                $order[$productId] = $quantity;
            }
        }

        $orders = $request->getSession()->get('orders', []);
        $orders[] = $order;
        $request->getSession()->set('orders', $orders);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart', name: 'app_cart')]
    public function cart(Request $request, ProductRepository $productRepository): Response
    {
        $totalCommand = 0;
        $orders = $request->getSession()->get('orders', []);

        $ordersWithProducts = [];
        foreach ($orders as $order) {
            $orderWithProducts = [];
            $totalPrice = 0;
            foreach ($order as $productId => $quantity) {
                $product = $productRepository->find($productId);
                $orderWithProducts[] = ['product' => $product, 'quantity' => $quantity];
                $totalPrice += $product->getPrice() * $quantity;
                $totalCommand += $product->getPrice() * $quantity;
            }
            $orderWithProducts['items'] = $orderWithProducts;
            $orderWithProducts['totalPrice'] = $totalPrice;
            $ordersWithProducts[] = $orderWithProducts;
        }

        return $this->render('command/cart.html.twig', [
            'totalCommand' => $totalCommand,
            'orders' => $ordersWithProducts,
        ]);
    }
    public function addOrder(Order2 $order)
    {
        // Vous pouvez sérialiser l'objet Order2 en JSON pour le stocker dans la session
        $this->session->set('order', json_encode($order));
    }

    #[Route('/clear-orders', name: 'app_clear_orders', methods: ['POST'])]
    public function clearOrders(Request $request): Response
    {
        $request->getSession()->remove('orders');

        return $this->redirectToRoute('app_cart');
    }
}
