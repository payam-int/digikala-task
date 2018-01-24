<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductsAdminController extends Controller
{
    /**
     * @Route("/administrator/products", name="products_admin")
     */
    public function index(Request $request)
    {
        $products_repository = $this->getDoctrine()->getRepository(Product::class);

        $index = 1;
        if ($request->get('page') !== null)
            $index = $request->get('page');

        $products_list = $products_repository->getList($index);

        return $this->render('admin/admin_products.html.twig', ['products' => $products_list]);
    }

    /**
     * @Route("/administrator/products/add", name="products_admin_add")
     */
    public function add(Request $request)
    {

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('products_admin');
        }

        return $this->render('admin/admin_product_add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/administrator/products/{product}/edit", name="products_admin_edit")
     */
    public function edit(Request $request, Product $product)
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('products_admin');
        }

        return $this->render('admin/admin_product_edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/administrator/products/{product}/delete", name="products_admin_delete")
     * @Method({"GET", "POST"})
     */
    public function delete(Request $request, Product $product)
    {
        if ($request->getMethod() == 'GET') {

            return $this->render('admin/admin_entity_delete.html.twig',
                [
                    'entity_type' => 'Product',
                    'entity_name' => $product->getTitle(),
                ]
            );

        } else {
            $orm = $this->getDoctrine()->getManager();
            $orm->remove($product);
            $orm->flush();

            return $this->redirectToRoute('products_admin');
        }

    }
}
