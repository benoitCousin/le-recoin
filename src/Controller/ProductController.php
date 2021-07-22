<?php

namespace App\Controller;

use App\Entity\Images;
use App\Entity\Product;
use App\Form\ProductsType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="product_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductsType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setActive(false);
            //recupération des img transmises 

            $images = $form->get('images')->getData();
             // boucle sur les img
            foreach($images as $image){
                //génére un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'. $image->guessExtension();
                // guessExtention genere le format = jpg png etc etc 
                
                //copie du fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

            // stock img dans bd ( son nom )

                $img = new Images();
                $img->setName($fichier);
                $product->addImage($img);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductsType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

                        //recupération des img transmises 
                        $product->setActive(false);
                        $images = $form->get('images')->getData();
                        // boucle sur les img
                       foreach($images as $image){
                           //génére un nouveau nom de fichier
                           $fichier = md5(uniqid()).'.'. $image->guessExtension();
                           // guessExtention genere le format = jpg png etc etc 
                           
                           //copie du fichier dans le dossier uploads
                           $image->move(
                               $this->getParameter('images_directory'),
                               $fichier
                           );
           
                       // stock img dans bd ( son nom )
           
                           $img = new Images();
                           $img->setName($fichier);
                           $product->addImage($img);
                       }
            $this->getDoctrine()->getManager()->flush();


            return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods={"POST"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/delete/image/{id}", name="product_delete_image", methods={"DELETE"})
     */
    public function deleteImage(Images $image, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        
        //verification du token 
        if($this->isCsrfTokenValid('delete'.$image->getId(),$data['_token'])){
            $name = $image->getName();
            unlink($this->getParameter('images_directory').'/'.$name);
            //sup
            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();
            //reponse
            return new JsonResponse(['success' => 1]);
        }else{
            return new JsonResponse(['error' =>'Token Invalide'], 400);
        }
    }
}
