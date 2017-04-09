<?php

namespace Pidev\UserBundle\Controller;

use Pidev\UserBundle\Entity\Membre;
use Pidev\UserBundle\Entity\Profil;
use Pidev\UserBundle\Form\ProfilType;
use Pidev\UserBundle\PidevUserBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Pidev\UserBundle\Repository ;

class ProfileController extends Controller
{
    public function initAction()
    {
        $profile = new Profil();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $form= $this->createForm(ProfilType::class) ;
        $currentUser = $this->get('doctrine.orm.entity_manager')
            ->getRepository('PidevUserBundle:Membre')
            ->find([
                'id' => $user
            ]);

        $profile=$this->get('doctrine.orm.entity_manager')->getRepository('PidevUserBundle:Profil')->findOneBy(
            array('idMembre' => $user))  ;

        if ($profile != null)

       {

            return $this->redirectToRoute('Profile',array('id'=>$id)) ;

        } else {

            return $this->render('PidevUserBundle:Front:ProfileMembre.html.twig', array('profile' => $profile,'form'=>$form->createView()));
        }


    }

    public function newProfileAction(Request $request)
    {
        $profile = new Profil();
        $form= $this->createForm(ProfilType::class) ;
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $currentUser = $this->get('doctrine.orm.entity_manager')
            ->getRepository('PidevUserBundle:Membre')
            ->find([
                'id' => $user
            ]);

        $form->handleRequest($request) ;

        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted()) {


            /*$profile->setIdMembre($currentUser);
            $profile->setDescription($request->get('Description'));
            $profile->setFacebook($request->get('facebook'));
            $profile->setGoogle($request->get('google'));
            $profile->setTwitter($request->get('twitter'));
            $profile->setLinkedin($request->get('linkedin'));
            $profile->setPhoto();
            $profile->setPhonenumber($request->get('phone'));
            $profile->setPseudo($request->get('pseudo'));*/


            $profile=$form->getData() ;
            $profile->setIdMembre($currentUser);








            $em->persist($profile);
            $em->flush();


        }

       return $this->render('PidevUserBundle:Front:ProfileMembre.html.twig', array('user' => $user, 'profile' => $profile,'form' => $form->createView()));

    }


    public function ListProfilesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $profiles = $this->get('doctrine.orm.entity_manager')->getRepository('PidevUserBundle:Profil')->findAll();
        return $this->render('PidevUserBundle:Front:ListMembres.html.twig', array('profiles' => $profiles));
    }

    public function RechercheProfileAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if ($request->isMethod('POST')) {
            $profiles = $em->getRepository('PidevUserBundle:Profil')->findAllBystring($request->get('tosearch'));
            return $this->render('PidevUserBundle:Front:ListMembres.html.twig', array('profiles' => $profiles));
        } else {
            return new Response('no memebres found') ;
        }
    }

    public function Affiche1ProfileAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $user=$em->getRepository('PidevUserBundle:Membre')->findOneBy(array('id'=>$id)) ;
        $profile=$em->getRepository('PidevUserBundle:Profil')->findOneBy(array('idMembre'=>$user));
        $pubs=$em->getRepository('PidevUserBundle:PublicationProfil')->findBy(array('idProfil'=>$profile));
        $test=$this->getDoctrine()->getRepository('PidevUserBundle:CommentaireProfile')->findAll();


        return $this->render('PidevUserBundle:Front:Profile.html.twig',array('Profile'=>$profile ,'Pubs'=>$pubs,'Coms'=>$test)) ;
    }


}