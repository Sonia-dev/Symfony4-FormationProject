<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Contact;
use App\Form\ContactType;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
//lancer maildev si ne marche pas executer la commande :maildev --hide-extensions STARTTLS pour tester l envoie du mail

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request ,MailerInterface $mailer): Response
    {

        $form=$this->createForm(ContactType::class);
        //formulaire gére la requete 
        $form->handleRequest($request);
    
        //si le formulaire a été soumis et que le formulaire a été valide 
        if($form->isSubmitted()&& $form ->isValid()){
            //recuperer les donnees
            $contactFormData = $form->getData();
           
            dump($contactFormData);
            $email=(new Email())
            //l'expéditeur
            ->from($contactFormData->getEmail())
            //le destinataire
            ->to('benabdallahsonia16@gmail.com')
            //objet du mail
            ->subject('You got mail')
            //recuperer le message a partir du formulaire 
            ->text($contactFormData->getMessage() ,
            'text/plain');
          //envoyer l email
            $mailer->send($email);
            //afficher message de succés 
            $this->addFlash('success','message envoyé !');
            $this->redirectToRoute('contact');

        }

        return $this->render('contact/index.html.twig', [
            //créer la vue du formulaire
            'our_form' => $form->createView()
        ]);
    }
}
