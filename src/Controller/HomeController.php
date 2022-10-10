<?php

namespace App\Controller;

use App\Entity\Images;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *@Route("/",name="home_")
 */
class HomeController extends AbstractController
    {
        
        /**
         * @Route("/", name="index")
         */
        public function index(ProductRepository $productRepository)
        {   
           
            return $this->render('home/index.html.twig', [
                'products' => $productRepository->findBy(['active' => false],['created_at'=>'desc']),
            ]);
            
        }
    }

