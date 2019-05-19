<?php

namespace QuizzyBundle\Controller;

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
        $friendService  = $this->get(FriendService::REFERENCE);
        $friends = $friendService->getFriendsByUser($user);
        $res = [];

        foreach ($friends as $friend) {
            $friend = $friend->getUser()->getId() === $user->getId() ? $friend->getUserSender() : $friend->getUser();
            $name = strtolower($friend->getLastName() . ' ' . $friend->getFirstName());

            if ($search != null && (substr_count($name, $search) === 0 && substr_count($friend->getEmail(), $search) === 0) ) {
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
        $friendService  = $this->get(FriendService::REFERENCE);
        $friend = $friendService->getFriends($currentUser, $user);

        if ($friend) {
            $this->em()->remove($friend);
            $this->em()->flush();
        }
        return new JsonResponse([], 200);
    }

    private function em()
    {
        return $this->getDoctrine()->getEntityManager();
    }
}