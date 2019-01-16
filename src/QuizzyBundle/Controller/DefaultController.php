<?php

namespace QuizzyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use QuizzyBundle\Entity\User;
use QuizzyBundle\Entity\Media;
use QuizzyBundle\Service\ImageService;


class DefaultController extends Controller
{

    /**
     * @Route("/login")
     */
    public function loginAction(Request $request)
    {
        $user = $this->em()->getRepository('QuizzyBundle:User')->findOneBy(["username" => $request->request->get('username'), "password" => $request->request->get('password')]);

        if ($user) {
            $res = [
            "status" => true,
            "id" => $user->getId(),
            "firstName" => $user->getFirstName(),
            "lastName" => $user->getLastName(),
            "username" => $user->getUsername(),
            "birthDate" => ["year" => (int)$user->getBirthDate()->format("Y"), "month" => (int)$user->getBirthDate()->format("m"), "day" => (int)$user->getBirthDate()->format("d")],
            "password" => $user->getPassword(),
            "email" => $user->getEmail(),
            "media" => $user->getMedia() ? $user->getMedia()->getPath() : null,
            ];
            return new JsonResponse($res, 200);
        } else {
            $res = ["status" => false];
            return new JsonResponse($res, 200);
        }
    }

    /**
     * @Route("/inscription")
     */
    public function inscriptionAction(Request $request)
    {
        $imageService  = $this->get(ImageService::REFERENCE);
        $usernameExist = $this->em()->getRepository('QuizzyBundle:User')->findOneBy(["username" => $request->request->get('username')]);
        $emailExist    = $this->em()->getRepository('QuizzyBundle:User')->findOneBy(["email" => $request->request->get('email')]);

        if ($usernameExist) {
            $res = [
            "status" => false,
            "error" => "username"
            ];
            return new JsonResponse($res, 200);
        } elseif ($emailExist) {
            $res = [
            "status" => false,
            "error" => "email"
            ];
            return new JsonResponse($res, 200);
        } else {


            $media = new Media();
            $media->setPath($imageService->saveImage($request->request->get('media'), "_user_profil.jpeg"));

            $user = new User();
            $user->setFirstName($request->request->get('prenom'));
            $user->setLastName($request->request->get('nom'));
            $user->setUsername($request->request->get('username'));
            $user->setBirthDate(new \DateTime($request->request->get('birthday')));
            $user->setEmail($request->request->get('email'));
            $user->setPassword($request->request->get('password'));
            $user->setMedia($media);

            $message = \Swift_Message::newInstance()
            ->setSubject('Bienvenue sur Quizzy !')
            ->setFrom(['quizzyAppli@gmail.com' => "Quizzy"])
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
                    'Emails/registration.html.twig',
                    array('user' => $user)
                    ),
                'text/html'
                );
            $this->get('mailer')->send($message);

            $this->em()->persist($media);
            $this->em()->persist($user);
            $this->em()->flush();

            $res = [
            "status" => true,
            "id" => $user->getId(),
            "media" => $media->getPath()
            ];

            return new JsonResponse($res, 200);
        }
    }

    private function em()
    {
        return $this->getDoctrine()->getEntityManager();
    }
}