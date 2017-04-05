<?php

namespace Pidev\UserBundle\Controller;

use Pidev\UserBundle\Entity\Membre;
use Pidev\UserBundle\Entity\Profil;
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
        $currentUser = $this->get('doctrine.orm.entity_manager')
            ->getRepository('PidevUserBundle:Membre')
            ->find([
                'id' => $user
            ]);

        if ($this->get('doctrine.orm.entity_manager')->getRepository('PidevUserBundle:Profil')->findBy(
                array('idMembre' => $user)
            ) != null
        ) {
            return new Response('Membre has profile ');

        } else {
            return $this->render('PidevUserBundle:Front:ProfileMembre.html.twig', array('profile' => $profile));
        }


    }

    public function newProfileAction(Request $request)
    {
        $profile = new Profil();

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $currentUser = $this->get('doctrine.orm.entity_manager')
            ->getRepository('PidevUserBundle:Membre')
            ->find([
                'id' => $user
            ]);

        $em = $this->getDoctrine()->getManager();

        if ($request->isMethod('POST')) {
            $profile->setIdMembre($currentUser);
            $profile->setDescription($request->get('Description'));
            $profile->setFacebook($request->get('facebook'));
            $profile->setGoogle($request->get('google'));
            $profile->setTwitter($request->get('twitter'));
            $profile->setLinkedin($request->get('linkedin'));
            $profile->setPhoto('http://www.hodaka-kenich.com/%E4%BC%8A%E8%97%A4%E5%9C%AD%EF%BC%8EJPG.jpg');
            $profile->setPhonenumber($request->get('phone'));
            $profile->setPseudo($request->get('pseudo'));

            $em->persist($profile);
            $em->flush();


        }
        return $this->render('PidevUserBundle:Front:ProfileMembre.html.twig', array('user' => $user, 'profile' => $profile));
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

    public function Affiche1Profile($id)
    {
        $em=$this->getDoctrine()->getManager();
        $profile=$em->getRepository('PidevUserBundle:Profil')->findOneBy(array('id'=>$id));

        return $this->render('PidevUserBundle:Front:Profile.html.twig',array('Profile'=>$profile)) ;
    }
}