<?php

namespace QuizzyBundle\Controller;

use QuizzyBundle\Entity\Friend;
use QuizzyBundle\Service\FriendService;
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
        $username = $request->request->get('username');
        $email = $request->request->get('email');

        $usernameExist = $this->em()->getRepository(User::REFERENCE)->findBy(['username' => $username]);
        if (count($usernameExist) > 0 && !(count($usernameExist) === 1 && $usernameExist[0]->getId() === $user->getId())) {
            return new JsonResponse([
                "status" => false,
                "error" => "username"
            ]);
        }

        $emailExist = $this->em()->getRepository(User::REFERENCE)->findBy(['email' => $email]);
        if (count($emailExist) > 0 && !(count($emailExist) === 1 && $emailExist[0]->getId() === $user->getId())) {
            return new JsonResponse([
                "status" => false,
                "error" => "email"
            ]);
        }

        $user->setLastName($lastName);
        $user->setFirstName($firstName);
        $user->setUsername($username);
        $user->setEmail($email);

        $this->em()->persist($user);
        $this->em()->flush();

        return new JsonResponse(
            ["status" => true], 200);
    }

    private function em()
    {
        return $this->getDoctrine()->getEntityManager();
    }
}