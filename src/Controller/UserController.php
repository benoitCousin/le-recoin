<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\EditProfileType;
use App\Form\ProductsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
    /**
     * @Route("/user/pass/edit", name="user_pass_edit")
     */
    public function editPass(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {   
        if($request->isMethod('post')){
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();

            //verification des mots de passe identique s
            if($request->request->get('pass') == $request->request->get('pass2')){
                
                $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('pass')));
                
                $em->flush();
                $this->addFlash('message' , 'Mot de passe mis à jour avec succès'); 
                    return $this->redirectToRoute('user');

            }else{
                $this->addFlash('error', 'Les mots de passe ne sont pas identiques');
            }
        }

        return $this->render('user/editpass.html.twig');
    }

}
