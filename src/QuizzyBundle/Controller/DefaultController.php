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
    	$user = $this->em()->getRepository('QuizzyBundle:User')->findOneBy(["username" => $request->request->get('username'), "password" => $request->request->get('mdp')]);

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
                "media" => $user->getMedia() ? base64_encode(file_get_contents($user->getMedia()->getPath())) : null,
            ];
            $res["friendList"] = $this->getFriendsList($user);

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

        if($usernameExist) {
            $res = [
                "status" => false,
                "error"  => "username"
            ];
            return new JsonResponse($res, 200);
        }
        elseif($emailExist) {
            $res = [
                "status" => false,
                "error"  => "email"
            ];
            return new JsonResponse($res, 200);
        }
        else{
            $media = new Media();
            $media->setPath($this->decodeImg64($request->request->get('media')));

            $user = new User();
            var_dump($request->request);
            $user->setFirstName($request->request->get('prenom'));
            $user->setLastName($request->request->get('nom'));
            $user->setUsername($request->request->get('username'));
            $user->setBirthDate(new \DateTime($request->request->get('birthday')));
            $user->setEmail($request->request->get('email'));
            $user->setPassword($request->request->get('mdp'));
            $user->setMedia($media);

            $this->em()->persist($media);
            $this->em()->persist($user);
            $this->em()->flush();

            $res = [
                "status" => true,
                "id"     => $user->getId()
            ];
            return new JsonResponse($res, 200);
        }
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

        if(!empty($request->request->get('media'))) {
            $media = new Media();
            $media->setPath($this->decodeImg64($request->request->get('media')));
            $quiz->setMedia($media);

            $this->em()->persist($media);
        }

        $this->em()->persist($quiz);
        $this->em()->flush();

        $res = ["quiz_id" => $quiz->getId()];
        return new JsonResponse($res, 200);
    }

    /*
     * Decode et sauvegarde l'img dans le dossier upload/image
     * Retourne le path de l'image
    */
    private function decodeImg64($base64)
    {
        $image_base64 = base64_decode($base64);
        $path = $this->get('kernel')->getRootDir()."/../web/upload/image/".uniqid().'_user_img.jpeg';
        file_put_contents($path, $image_base64);
        
        return $path;
    }

    /*
     * Retourne la liste d'amis d'une utilisateur
    */
    private function getFriendsList(User $user)
    {
        $friends = [];
        foreach ($user->getFriendsList() as $friend) {
            $data = [
                "id" => $friend->getId(),
                "firstName" => $friend->getFirstName(),
                "lastName" => $friend->getLastName(),
                "username" => $friend->getUsername(),
                "birthDate" => ["year" => (int)$friend->getBirthDate()->format("Y"), "month" => (int)$friend->getBirthDate()->format("m"), "day" => (int)$friend->getBirthDate()->format("d")],
                "email" => $friend->getEmail(),
                "media" => $friend->getMedia() ? base64_encode(file_get_contents($friend->getMedia()->getPath())) : null,
            ];
            array_push($friends, $data);
        }

        return $friends;
    }

    private function em(){
    	return $this->getDoctrine()->getEntityManager();
    }
}
