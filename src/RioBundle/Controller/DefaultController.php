<?php

namespace RioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('RioBundle:Epreuve')
            ;

        $EpreuveArr = $repository->FindByLastEpreuve();




        return $this->render('RioBundle:Default:index.html.twig', array(
            'EpreuveArr' => $EpreuveArr
        ));
    }

    public function athAction($idEpreuve)
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('RioBundle:Athlete')
            ;

        $athletes = $repository->FindAllByEpreuve($idEpreuve);

        return $this->render('RioBundle:Default:athlete.html.twig', array(
            'athletes' => $athletes
        ));
    }

    public function voteAction(Request $request)
    {
        $vote = $request->request->get('athvote');

        $em = $this->getDoctrine()->getManager();
         $query = $em->createQuery(
             'UPDATE RioBundle:Athlete f
              SET f.score = f.score + 1'
         );

        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('RioBundle:Athlete')
            ;

            $ath = $repository->findOneByNom($vote);

            var_dump($ath); exit;






        return $this->render('RioBundle:Default:vote.html.twig');
    }
}
