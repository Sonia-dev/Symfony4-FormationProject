<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FormationRepository;
use App\Repository\ContactRepository;
use App\Notification\ContactNotification;
use App\Entity\Formation;
use App\Form\FormationType;




/**
 * @Route("/formation")
 */

class FormationController extends AbstractController
{
    /**
     * @Route("/liste", name="formation_list")
     */
    public function index(FormationRepository $FormationRepository ) 
    {
        return $this->render('formation/index.html.twig', [
            //retrouver tout les formation
            'Formations' => $FormationRepository->findAll(),
        ]);
    }

    /**
     * @Route ("/acceuil",name="acceuil")
     */
    public function home()
    {
        return $this->render('formation/home.html.twig');
    }
    
    /**
     * @Route ("/about",name="about")
     */
    public function about()
    {
        return $this->render('formation/about.html.twig');
    }




    /**
     * @Route("/new", name="new_formation" , methods={"GET","POST"})
     */
    public function new(Request $request){
        //nouvelle instance
        $formation=new Formation();
        $form=$this->createForm(FormationType::class,$formation);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form -> isValid()){

            $imageFile=$form->get('image')->getData();
            //on genere un nouveau nom d image 
            $fileName= md5(uniqid()).'.' .$imageFile->guessExtension();
            //on copie l'image dans le dossier uploads 
            $imageFile->move($this->getParameter('image_directory'),$fileName);
          
            $formation->setImage($fileName)
                       ->setUpdatedAt( new \DateTime());
            $formation=$form->getData();
            //va effectuer la requete d 'update dans la base de donnees
            $entityManager= $this ->getDoctrine()->getManager();
            $entityManager->persist($formation);
            $entityManager->flush();
            return $this-> redirect($this->generateUrl('formation_list'));
        }
        return $this->render('formation/new.html.twig',['form'=>$form->createView()]);
    }

    /**
     * @Route("/{id}",name="detail_formation",methods={"GET","POST"})
     */
    public function detail( Formation $formation, Request $request  ):Response
    {

   
        return $this->render('formation/detail.html.twig',[
            'formation'=>$formation,
        
        ]);
    }

    /**
     * @Route("/{id}/edit",name="edit_formation",methods={"GET","POST"})
     */
    public function edit(Formation $formation,request $request):Response
    {
        $form=$this->createForm(FormationType::class,$formation);
        //affecter les attributs de l'objet formation avec avec les valeurs issues de la requete 
        $form->handleRequest($request);
        //verifier si le formulaire est bien soumis et validéS
        if($form->isSubmitted()&& $form ->isValid()){
            $entityManager= $this ->getDoctrine()->getManager();
            $entityManager->flush();
            //rederige la page 
            return $this ->redirectToRoute('formation_list');

        }
        return $this ->render('formation/edit.html.twig',
        [
            'formation'=>$formation,
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}",name="supprimer_formation",methods={"GET"})
     */
    public function supprimer(Formation $formation,request $request,$id):Response 
    {
        //recherche de la formation en fonction de l id 
       $formation=$this->getDoctrine()->getRepository(Formation::class)->find($id);
        $entityManager= $this ->getDoctrine()->getManager();
        //supprimer l'instance de l entité formation en argument
        $entityManager->remove($formation);
        //synchroniser l etat de l instance avec la table dans la BD
        $entityManager->flush();
       
    $response=new Response();
    $response->send();


    return $this->redirectToRoute('formation_list');
    }
}
