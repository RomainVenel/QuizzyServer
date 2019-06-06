<?php

namespace QuizzyBundle\Controller;

use QuizzyBundle\Entity\Friend;
use QuizzyBundle\Entity\Media;
use QuizzyBundle\Service\FriendService;
use QuizzyBundle\Service\ImageService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use QuizzyBundle\Entity\User;

class UserController extends Controller
{

    /**
     * @Route("/friends/{user}")
     * @param Request $request
     * @param $user
     * @return JsonResponse
     */
    public function getFriendListAction(Request $request, $user)
    {
        $user = $this->em()->getRepository(User::REFERENCE)->find($user);
        $search = strtolower($request->request->get('search'));
        $friendService = $this->get(FriendService::REFERENCE);
        $friends = $friendService->getFriendsByUser($user);
        $res = [];

        foreach ($friends as $friend) {
            $friend = $friend->getUser()->getId() === $user->getId() ? $friend->getUserSender() : $friend->getUser();

            if ($search != null && (substr_count(strtolower($friend->getUsername()), $search) === 0 && substr_count(strtolower($friend->getEmail()), $search) === 0)) {
                continue;
            }

            $res[] = [
                "id" => $friend->getId(),
                "firstName" => $friend->getFirstName(),
                "lastName" => $friend->getLastName(),
                "username" => $friend->getUsername(),
                "birthDate" => ["year" => (int)$friend->getBirthDate()->format("Y"), "month" => (int)$friend->getBirthDate()->format("m"), "day" => (int)$friend->getBirthDate()->format("d")],
                "password" => $friend->getPassword(),
                "email" => $friend->getEmail(),
                "media" => $friend->getMedia() ? $friend->getMedia()->getPath() : null,
            ];
        }
        return new JsonResponse($res, 200);
    }

    /**
     * @Route("/friend/delete/{currentUser}/{user}")
     * @param $currentUser
     * @param $user
     * @return JsonResponse
     */
    public function deleteFriendAction($currentUser, $user)
    {
        $currentUser = $this->em()->getRepository(User::REFERENCE)->find($currentUser);
        $user = $this->em()->getRepository(User::REFERENCE)->find($user);
        $friendService = $this->get(FriendService::REFERENCE);
        $friend = $friendService->getFriends($currentUser, $user);

        if ($friend) {
            $this->em()->remove($friend);
            $this->em()->flush();
        }
        return new JsonResponse([], 200);
    }

    /**
     * @Route("/friend/{user}/request")
     * @param $user
     * @return JsonResponse
     */
    public function getFriendsRequestAction($user)
    {
        $user = $this->em()->getRepository(User::REFERENCE)->find($user);
        $friendService = $this->get(FriendService::REFERENCE);
        $friendsRequest = $friendService->getFriendsRequestByUser($user);
        $res = [];

        foreach ($friendsRequest as $friend) {
            $friend = $friend->getUserSender();
            $res[] = [
                "id" => $friend->getId(),
                "firstName" => $friend->getFirstName(),
                "lastName" => $friend->getLastName(),
                "username" => $friend->getUsername(),
                "birthDate" => ["year" => (int)$friend->getBirthDate()->format("Y"), "month" => (int)$friend->getBirthDate()->format("m"), "day" => (int)$friend->getBirthDate()->format("d")],
                "password" => $friend->getPassword(),
                "email" => $friend->getEmail(),
                "media" => $friend->getMedia() ? $friend->getMedia()->getPath() : null,
            ];
        }

        return new JsonResponse($res, 200);
    }

    /**
     * @Route("/friend/request/{currentUser}/{userSender}/{choice}")
     * @param $currentUser
     * @param $userSender
     * @param $choice
     * @return JsonResponse
     */
    public function choiceFriendRequestAction($currentUser, $userSender, $choice)
    {
        $currentUser = $this->em()->getRepository(User::REFERENCE)->find($currentUser);
        $userSender = $this->em()->getRepository(User::REFERENCE)->find($userSender);
        $friendRequest = $this->em()->getRepository(Friend::REFERENCE)->findOneBy(['user_sender' => $userSender, 'user' => $currentUser]);

        if ($friendRequest) {
            if ($choice) {
                $friendRequest->setAccepted(true);
                $this->em()->persist($friendRequest);
            } else {
                $this->em()->remove($friendRequest);
            }
        }

        $this->em()->flush();
        return new JsonResponse([], 200);
    }

    /**
     * @Route("/friend/add/possible/{user}")
     * @param Request $request
     * @param $user
     * @return JsonResponse
     */
    public function getUsersCanAddAsFriendAction(Request $request, $user)
    {
        $user = $this->em()->getRepository(User::REFERENCE)->find($user);
        $friendService = $this->get(FriendService::REFERENCE);
        $friendsCanBeAdd = $friendService->getFriendsListCanBeAdd($user, strtolower($request->request->get('search')));
        $res = [];

        foreach ($friendsCanBeAdd as $friend) {
            $res[] = [
                "id" => $friend->getId(),
                "firstName" => $friend->getFirstName(),
                "lastName" => $friend->getLastName(),
                "username" => $friend->getUsername(),
                "birthDate" => ["year" => (int)$friend->getBirthDate()->format("Y"), "month" => (int)$friend->getBirthDate()->format("m"), "day" => (int)$friend->getBirthDate()->format("d")],
                "password" => $friend->getPassword(),
                "email" => $friend->getEmail(),
                "media" => $friend->getMedia() ? $friend->getMedia()->getPath() : null,
            ];
        }

        return new JsonResponse($res, 200);
    }

    /**
     * @Route("/add/friend/{userSender}/{user}")
     * @param $userSender
     * @param $user
     * @return JsonResponse
     */
    public function addFriendAction($userSender, $user)
    {
        $userSender = $this->em()->getRepository(User::REFERENCE)->find($userSender);
        $user = $this->em()->getRepository(User::REFERENCE)->find($user);
        $isFriendBySender = $this->em()->getRepository(Friend::REFERENCE)->findBy(['user_sender' => $userSender, 'user' => $user]);
        $isFriendByUser = $this->em()->getRepository(Friend::REFERENCE)->findBy(['user_sender' => $user , 'user' => $userSender]);

        if (!$isFriendBySender && !$isFriendByUser) {
            $newFriend = new Friend();
            $newFriend->setUserSender($userSender);
            $newFriend->setUser($user);
            $newFriend->setAccepted(false);

            $this->em()->persist($newFriend);
            $this->em()->flush();
        }

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/update/profil/{user}")
     * @param Request $request
     * @param $user
     * @return JsonResponse
     */
    public function updateProfilAction(Request $request, $user)
    {
        $user = $this->em()->getRepository(User::REFERENCE)->find($user);
        $lastName = $request->request->get('last_name');
        $firstName = $request->request->get('first_name');
        $email = $request->request->get('email');

        $emailExist = $this->em()->getRepository(User::REFERENCE)->findBy(['email' => $email]);
        if (count($emailExist) > 0 && !(count($emailExist) === 1 && $emailExist[0]->getId() === $user->getId())) {
            return new JsonResponse(["status" => false]);
        }

        $imageService  = $this->get(ImageService::REFERENCE);
        if ($user->getMedia()) {// on delete l'image si il y a en
            $imageService->deleteImage($user->getMedia());
        }

        $user->setMedia(null);
        if (!empty($request->request->get('media'))) {
            $media = new Media();
            $media->setPath($imageService->saveImage($request->request->get('media'), "_user_img.jpeg"));
            $user->setMedia($media);
            $this->em()->persist($media);
        }

        $user->setLastName($lastName);
        $user->setFirstName($firstName);
        $user->setEmail($email);

        $this->em()->persist($user);
        $this->em()->flush();

        return new JsonResponse(
            [
                "status" => true,
                "media" => $user->getMedia() != null ? $user->getMedia()->getPath() : null
            ],
            200);
    }

    /**
     * @Route("/forget/password")
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function forgetPasswordAction(Request $request)
    {
        $username = $request->request->get('username');
        $birthday = new \DateTime($request->request->get('birthday'));
        $user = $this->em()->getRepository(User::REFERENCE)->findOneBy(['username' => $username, "birthDate" => $birthday]);

        if (!$user) {
            return new JsonResponse(["status" => false], 200);
        }

        $password = $this->generateRandomPassword();
        $message = \Swift_Message::newInstance()
            ->setSubject('Votre nouveau mot de passe !')
            ->setFrom(['quizzyAppli@gmail.com' => "Quizzy"])
            ->setTo($user->getEmail())
            ->setBody($this->renderView('Emails/forgetPassword.html.twig', ['user' => $user, 'password' => $password]), 'text/html');
        $this->get('mailer')->send($message);

        $user->setPassword(md5($password));
        $this->em()->persist($user);
        $this->em()->flush();

        return new JsonResponse(["status" => true], 200);
    }

    /**
     * @Route("/user/{user}/change/password")
     * @param Request $request
     * @param $user
     * @return JsonResponse
     * @throws \Exception
     */
    public function changePasswordAction(Request $request, $user)
    {
        $user = $this->em()->getRepository(User::REFERENCE)->find($user);
        if ($user) {
            $user->setPassword($request->request->get('password'));
            $this->em()->persist($user);
            $this->em()->flush();
        }

        return new JsonResponse(["status" => true], 200);
    }

    /**
     * @return string
     */
    private function generateRandomPassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = '';
        for ($i = 0; $i < 8; $i++) {
            $pass .= $alphabet[rand(0, strlen($alphabet) - 1)];
        }

        return $pass;
    }

    /**
     * @return mixed
     */
    private function em()
    {
        return $this->getDoctrine()->getEntityManager();
    }
}