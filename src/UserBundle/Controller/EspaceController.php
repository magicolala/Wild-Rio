<?php

namespace UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;
use UserBundle\Entity\Datauser;
use UserBundle\Form\DatauserType;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;


class EspaceController extends Controller
{
    /**
     * @Route("/", name="user_espace")
     */
    public function espaceAction(Request $request)
    {

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $em = $this->getDoctrine()->getManager();

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

            $userManager->updateUser($user);

            $thisData = new Datauser();

            $thisData->setUserId($user->getId());

            $userNom = $request->request->get('userNom');
            $userPrenom = $request->request->get('userPrenom');
            $userFonction = $request->request->get('userFonction');
            $userVille = $request->request->get('userVille');
            $userImage = $request->request->get('userImage');


            $thisData->setUserId($user->getId());
            $thisData->setUserNom($userNom);
            $thisData->setUserPrenom($userPrenom);
            $thisData->setUserFonction($userFonction);
            $thisData->setUserVille($userVille);
        
            $em->persist($thisData);
            $em->flush();
        }

        $allUser = [];

        $users = $em->getRepository('UserBundle:User')->findAll();
        foreach ($users as $utilisateur)
        {
            $thisUser = $em->getRepository('UserBundle:Datauser')->findOneByUserId($utilisateur->getId());
            $allUser[] = array(
                'id' => $utilisateur->getId(),
                'pseudo' => $utilisateur->getUsername(),
                'email' => $utilisateur->getEmail(),
                'nom' => $thisUser->getUserNom(),
                'prenom' => $thisUser->getUserPrenom(),
                'fonction' => $thisUser->getUserFonction(),
                'ville' => $thisUser->getUserVille(),
                'image' => $thisUser->getUserImage(),
            );
        }

        $user = $this->container->get('security.context')->getToken()->getUser();
        $request = $em->getRepository('UserBundle:Datauser')->findOneByUserId($user->getId());
       return $this->render('UserBundle:Espace:espace.html.twig', array(
            'requete' => $request,
            'form' => $form->createView(),
            'allUsers' => $allUser,
        ));
    }

    public function espacemodifAction(Request $request, $id)
    {
        
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        // create a task and give it some dummy data for this example

       
        $datauser = $em->getRepository('UserBundle:Datauser')->findOneByUserId($id);

        $form = $this->createForm(DatauserType::class, $datauser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        // $file stores the uploaded PDF file
        /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
        $file = $datauser->getUserImage();

        $datauser->setUserId($id);


        // Generate a unique name for the file before saving it
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        // Move the file to the directory where brochures are stored
        $file->move(
            $this->container->getParameter('datausers_directory'),
            $fileName
        );

        // Update the 'brochure' property to store the PDF file name
        // instead of its contents
        $datauser->setUserImage($fileName);

        $em->persist($datauser);
        $em->flush();

        // ... persist the $product variable or any other work
        return $this->redirect($this->generateUrl('user_espacemodif', array('id'=>$id)));
        }

        $request = $em->getRepository('UserBundle:Datauser')->findOneByUserId($user->getId());
        return $this->render('UserBundle:Espace:espacemodif.html.twig', array(
            'form' => $form->createView(),
            'datauser' => $datauser,
            'requete' => $request,
        )); 
    }

    public function espacedeleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();

        $supp = $em->getRepository('UserBundle:User')->findOneById($id);

        $em->remove($supp);
        $em->flush();

        return $this->redirect($this->generateUrl('user_espace'));

    }

}
