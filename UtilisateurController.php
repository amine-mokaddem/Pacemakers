<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\LoginType;
use App\Form\UtilisateurType;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class UtilisateurController extends AbstractController
{
    /**
     * @Route("", name="utilisateur")
     */
    public function affiche(): Response
    {
        return $this->render('utilisateur/accueil.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function verif(Request $request)
    {
        $User = new Utilisateur();
        $form = $this->createForm(LoginType::class, $User);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $nom = $User->getNom();
            $password = $User->getPassword();
            $user1 = $this->getDoctrine()->getRepository(Utilisateur::class)->findOneBy(array('nom' => $nom, 'password' => $password));

            if (!$user1) {
                return $this->redirectToRoute("login");
            } else {
                $type = $user1->getType();
                if ($type == 'admin') {
                    return $this->redirectToRoute("back");
                } else {
                    if ($type == 'client') {
                        return $this->redirectToRoute("home");
                    }
                }
            }
        }
        return $this->render("utilisateur/login.html.twig", array("form" => $form->createView()));
    }


    /**
     * @Route("creer", name="creer")
     */
    public function ajoutcompte(Request $request){
        $util= new Utilisateur();
        $form= $this->createForm(UtilisateurType::class, $util);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $util->setType('client');
            $em= $this->getDoctrine()->getManager();
            $em->persist($util);
            $em->flush();
            return $this->redirectToRoute("login");
        }
        return $this->render("utilisateur/creer.html.twig",array("form"=>$form->createView()));

    }








    /**
     * @Route("ajouterU", name="ajouterU")
     */
    public function ajoutvelo(Request $request){
        $util= new Utilisateur();
        $form= $this->createForm(UtilisateurType::class, $util);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em= $this->getDoctrine()->getManager();
            $em->persist($util);
            $em->flush();
            return $this->redirectToRoute("afficherU");
        }
        return $this->render("utilisateur/ajouterU.html.twig",array("form"=>$form->createView()));

    }


    /**
     * @Route("afficherU", name="afficherU")
     */
    public function affiche_velo(){
        $util=$this->getDoctrine()->getRepository(Utilisateur::class)->findAll();
        return $this->render("utilisateur/afficherU.html.twig", ['util'=>$util]);
    }

    /**
     * @Route("supprimerU{id}", name="supprimerU")
     */
    public function supprimer_velo($id){
        $util = $this->getDoctrine()->getRepository(Utilisateur::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($util);
        $em->flush();
        return $this->redirectToRoute("afficherU");
    }
    /**
     * @Route("modifierU{id}", name="modifierU")
     */
    public function updatevelo($id , Request $request){
        $util= $this->getDoctrine()->getRepository(Utilisateur::class)->find($id);
        $form= $this->createForm(UtilisateurType::class, $util);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em= $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("afficherU");}

        return $this->render("utilisateur/modifierU.html.twig",array("form"=>$form->createView()));
    }
















}
