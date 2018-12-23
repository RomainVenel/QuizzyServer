<?php

namespace QuizzyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use QuizzyBundle\Entity\User;
use QuizzyBundle\Entity\Media;
use QuizzyBundle\Entity\Quiz;

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
        }
        else {
            $res = ["status" => false];
            return new JsonResponse($res, 200);
        }
    }

    /**
     * @Route("/inscription")
     */
    public function inscriptionAction(Request $request)
    {
        $usernameExist = $this->em()->getRepository('QuizzyBundle:User')->findOneBy(["username" => $request->request->get('username')]);

        $emailExist = $this->em()->getRepository('QuizzyBundle:User')->findOneBy(["email" => $request->request->get('email')]);

        if ($usernameExist) {
            $res = [
                "status" => false,
                "error"  => "username"
            ];
            return new JsonResponse($res, 200);
        }
        elseif ($emailExist) {
            $res = [
                "status" => false,
                "error"  => "email"
            ];
            return new JsonResponse($res, 200);
        } else {
            $media = new Media();
            $media->setPath($this->saveImage($request->request->get('media'), "_user_profil.jpeg"));

            $user = new User();
            $user->setFirstName($request->request->get('prenom'));
            $user->setLastName($request->request->get('nom'));
            $user->setUsername($request->request->get('username'));
            $user->setBirthDate(new \DateTime($request->request->get('birthday')));
            $user->setEmail($request->request->get('email'));
            $user->setPassword($request->request->get('password'));
            $user->setMedia($media);

            $this->em()->persist($media);
            $this->em()->persist($user);
            $this->em()->flush();

            $res = [
                "status" => true,
                "id"     => $user->getId(),
                "media"  => $media->getPath() 
            ];
            return new JsonResponse($res, 200);
        }
    }

    /**
     * @Route("/{user}/quiz/{finished}", requirements={"user" = "\d+"})
     */
    public function getQuizAction(Request $request, $user, $finished)
    {
        $user    = $this->em()->getRepository('QuizzyBundle:User')->find($user);
        $allQuiz = $this->em()->getRepository('QuizzyBundle:Quiz')->findBy(["user" => $user]);
        $res     = [];
        foreach ($allQuiz as $quiz) {
            $add = null;
            if ($finished) {
                $quiz->getIsValidated() != null ? $add = $quiz : "";
            } else {
                $quiz->getIsValidated() == null ? $add = $quiz : "";
            }

            if ($add != null) {
                $tab = [
                    "id"         => $quiz->getId(),
                    "name"       => $quiz->getName(),
                    "popularity" => $quiz->getPopularity() != null ? $quiz->getPopularity() : null,
                    "media"      => $quiz->getMedia() ? $quiz->getMedia()->getPath() : null
                ];
                if ($quiz->getIsValidated() != null) {
                    $tab["isValidated"] = [
                        "year"  => (int)$user->getBirthDate()->format("Y"),
                        "month" => (int)$user->getBirthDate()->format("m"),
                        "day"   => (int)$user->getBirthDate()->format("d")
                    ];
                }
                array_push($res, $tab);
            }
        }

        return new JsonResponse($res, 200);
    }

    /**
     * @Route("/{user}/quiz/new", requirements={"user" = "\d+"})
     */
    public function newQuizAction(Request $request, $user)
    {
        $user = $this->em()->getRepository('QuizzyBundle:User')->find($user);

        $quiz = new Quiz();
        $quiz->setName($request->request->get('name'));
        $quiz->setUser($user);

        if (!empty($request->request->get('media'))) {
            $media = new Media();
            $media->setPath($this->saveImage($request->request->get('media'), "_quiz_img.jpeg"));
            $quiz->setMedia($media);

            $this->em()->persist($media);
        }

        $this->em()->persist($quiz);
        $this->em()->flush();

        $res = [
            "id"    => $quiz->getId(),
            "media" => $quiz->getMedia() != null ? $quiz->getMedia()->getPath() : null
        ];        
        return new JsonResponse($res, 200);
    }

    /**
     * @Route("/quiz/edit/{quiz}", requirements={"quiz" = "\d+"})
     */
    public function setQuizAction(Request $request, $quiz) // revoir ici pour le edit/new
    {
        $quiz = $this->em()->getRepository('QuizzyBundle:Quiz')->find($quiz);
        
        if ($quiz->getMedia()) {// on delete l'image si il y a en
            unlink($quiz->getMedia()->getPath());
            $this->em()->remove($quiz->getMedia());
        }

        $quiz->setMedia(null);
        $quiz->setName($request->request->get('name'));

        if (!empty($request->request->get('media'))) {
            $media = new Media();
            $media->setPath($this->saveImage($request->request->get('media'), "_quiz_img.jpeg"));
            $quiz->setMedia($media);

            $this->em()->persist($media);
        }

        $this->em()->persist($quiz);
        $this->em()->flush();

        $res = [
            "media" => $quiz->getMedia() != null ? $quiz->getMedia()->getPath() : null
        ];        
        return new JsonResponse($res, 200);
    }

    /*
     * Decode et sauvegarde l'image dans le dossier upload/image
     * Retourne le path de l'image
    */
    private function saveImage($base64, $name)
    {
        $imageBase64 = base64_decode($base64);
        $nameFile    = "upload/image/" . uniqid() . $name;
        file_put_contents(
            $this->get('kernel')->getRootDir()."/../web/" . $nameFile,
            $imageBase64
        );
        
        return $nameFile;
    }

    private function em(){
    	return $this->getDoctrine()->getEntityManager();
    }
}
