<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\EditProfileType;
use App\Form\ProductsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig');
    }
    /**
     * @Route("/user/product/ajout", name="user_product_ajout")
     */
    public function ajoutProduct(Request $request)
    {   
        $product = new Product;
        $form = $this->createForm(ProductsType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid()){
            $product->setUser($this->getUser());
            $product->setActive(false);

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('user');
        }

        return $this->render('user/product/ajout.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/user/profile/edit", name="user_profile_edit")
     */
    public function editProfile(Request $request)
    {   
        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $this->getUser());
        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('message', 'profil mis à jour');

            return $this->redirectToRoute('user');
        }

        return $this->render('user/editprofile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
