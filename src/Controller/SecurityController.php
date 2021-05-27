<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Form\RegistrationType;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request , UserPasswordEncoderInterface $passwordEncoder ) : Response
    {

        $user =new User ();
        $form= $this->createForm(RegistrationType :: class ,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form ->isValid())
        {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
           
           
            $entityManager = $this->getDoctrine()->getManager();
             
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('security_login');
           
        }


        return $this->render('security/registration.html.twig', [
            'form' => $form ->createView() 
        ]);
        }

        /**
         * @Route("login" , name="security_login")
         */
            public function login(){

                return $this ->render('security/login.html.twig');
                       
            
            }

            //voir security.yaml 

        /**
         * @Route("logout" , name="security_logout")
         */
        public function logout(){

            return $this ->render('security/login.html.twig');
                   
        
        }






}
