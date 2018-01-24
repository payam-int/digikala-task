<?php

namespace App\Controller\Admin;

use App\Entity\Variant;
use App\Form\VariantType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class VariantsAdminController extends Controller
{
    /**
     * @Route("/administrator/variants", name="variants_admin")
     */
    public function index(Request $request)
    {
        $variants_repository = $this->getDoctrine()->getRepository(Variant::class);

        $index = 1;
        if ($request->get('page') !== null)
            $index = $request->get('page');

        $variants_list = $variants_repository->getList($index);

        return $this->render('admin/admin_variants.html.twig', ['variants' => $variants_list]);
    }

    /**
     * @Route("/administrator/variants/add", name="variants_admin_add")
     */
    public function add(Request $request)
    {

        $variant = new Variant();
        $form = $this->createForm(VariantType::class, $variant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($variant);
            $em->flush();

            return $this->redirectToRoute('variants_admin');
        }

        return $this->render('admin/admin_variant_add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/administrator/variants/{variant}/edit", name="variants_admin_edit")
     */
    public function edit(Request $request, Variant $variant)
    {
        $form = $this->createForm(VariantType::class, $variant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($variant);
            $em->flush();

            return $this->redirectToRoute('variants_admin');
        }

        return $this->render('admin/admin_variant_edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/administrator/variants/{variant}/delete", name="variants_admin_delete")
     * @Method({"GET", "POST"})
     */
    public function delete(Request $request, Variant $variant)
    {
        if ($request->getMethod() == 'GET') {
            return $this->render('admin/admin_entity_delete.html.twig',
                [
                    'entity_type' => 'Product',
                    'entity_name' => $variant->getName(),
                ]
            );

        } else {
            $orm = $this->getDoctrine()->getManager();
            $orm->remove($variant);
            $orm->flush();

            return $this->redirectToRoute('variants_admin');
        }

    }
}
