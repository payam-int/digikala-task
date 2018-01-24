<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Variant;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProductsController
 * @package App\Controller
 *
 * This class controls listing products, showing a single product, searching in products.
 */
class ProductsController extends Controller
{
    /**
     * @Route("/products", name="products")
     */
    public function index(Request $request)
    {
        $products_repository = $this->getDoctrine()->getRepository(Product::class);

        // get page from request or set it to 1
        $index = 1;
        if ($request->get('page') !== null)
            $index = $request->get('page');


        /*
         * Get the list of products from ProductRepository.
         * For simplicity, ProductRepository::getList returns a Pagerfanta object.
         */
        $products_list = $products_repository->getList($index);

        return $this->render('products/list.html.twig', ['products' => $products_list]);
    }

    /**
     * @Route("/products/{product}/show", name="products_show")
     * @Cache(expires="+1 hours")
     */
    public function show(Request $request, Product $product)
    {
        return $this->render('products/show.html.twig', ['product' => $product]);
    }

    /**
     * @Route("/products/search", name="products_search")
     */
    public function search(Request $request)
    {
        $products_repository = $this->getDoctrine()->getRepository(Product::class);

        $query = $request->get('q');

        $products = $products_repository->search($query);

        return $this->render('products/search.html.twig', ['products' => $products, 'search_query' => $query]);
    }
}
