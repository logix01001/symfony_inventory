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
    public function show(Request $request): Response
    {

        if($request->get('id')){
            $id = $request->get('id');
            $em = $this->getDoctrine()->getManager();
            $entity = ($id != null ) ? $em->getRepository(Product::class)->find($id) : null;
    

            if( $entity == null){
               // return $this->redirectMainPage();
            }

            $form = $this->createForm(
                ProductFormType::class, 
                $entity,
                [
                    'action' => $this->generateUrl('product.update',['id' => $id]),
                    'method' => 'PUT',
                ]);

        }else{
            $form = $this->createForm(
                ProductFormType::class, 
                null,
                [
                    'action' => $this->generateUrl('product.store'),
                    'method' => 'POST',
                ]);
        }

        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/store", name="product.store", methods={"POST"})
     */
    public function store(Request $request)
    {
      
        $product = new Product();
        $form = $this->createForm(ProductFormType::class,$product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash(
                'notice',
                'New product has been successfully saved!.'
            );
        }
       
        return $this->redirectMainPage();
    }


    /**
     * @Route("/update/{id}", name="product.update", methods={"PUT"})
     */
    public function update(int $id,Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($id);
        
        $form = $this->createForm(ProductFormType::class, $product, ['method' => 'PUT']);

        
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
            
            $product = $form->getData();
            $em->persist($product);
            $em->flush();
          
            $this->addFlash(
                'notice',
                "{$product->getName()} has been successfully updated!."
            );
        }

        return $this->redirectMainPage();
    }

     /**
     * @Route("/destroy/{id}", name="product.delete", methods={"DELETE"})
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


    private function redirectMainPage()
    {
        return $this->redirect( $this->generateUrl('main.index'));
    }


}
