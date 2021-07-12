<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product.show")
     */
    public function show(): Response
    {


        $form = $this->createForm(
            ProductFormType::class, 
            null,
            [
                'action' => $this->generateUrl('product.store'),
                'method' => 'POST',
            ]);

        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/store", name="product.store", methods="POST")
     */
    public function store(Request $request)
    {

        $product = new Product();
        $form = $this->createForm(ProductFormType::class,   $product);

        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
        }

        return $this->redirect( $this->generateUrl('main.index'));
    }

     /**
     * @Route("/destroy/{id}", name="product.delete", methods="DELETE")
     */
    public function destroy(int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository(Product::class)->find($id);

        if ($entity != null){
            $em->remove($entity);
            $em->flush();
        }
        
        
       return $this->redirect( $this->generateUrl('main.index'));
    }



}
