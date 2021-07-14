<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\MainSearchType;
use App\Form\ProductFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main.index")
     */
    public function index(Request $request): Response
    {

        //GETTING ALL DATA
        $products = $this->getDoctrine()
          ->getRepository(Product::class)
          ->findAll();
        
        $form = $this->createForm(MainSearchType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

          $search =  $form->get('search')->getData();
          $products = $this->getDoctrine()
              ->getRepository(Product::class)
              ->SearchString($search);
        
        }

        //Passing data to VIEW
        return $this->render('main/index.html.twig', [
          'controller_name' => 'MainController',
          'products' => $products,
          'form' =>  $form->createView()
        ]);
    }
}
