<?php

namespace QuizzyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use QuizzyBundle\Entity\Media;
use QuizzyBundle\Entity\Part;
use QuizzyBundle\Service\ImageService;

class PartController extends Controller
{

	/**
     * @Route("/quiz/{quiz}", requirements={"quiz" = "\d+"})
     */
    public function getPartsAction(Request $request, $quiz)
    {
        $parts = $this->em()->getRepository('QuizzyBundle:Part')->findBy(["quiz" => $quiz]);
        $res = [];

        foreach ($parts as $part) {
            $data = [
                "id" => $part->getId(),
                "name" => $part->getName(),
                "desc" => $part->getDescription(),
                "media" => $part->getMedia() != null ? $part->getMedia()->getPath() : null
            ];
            array_push($res, $data);
        }

        return new JsonResponse($res, 200);
    }

    /**
     * @Route("/quiz/{quiz}/part/new", requirements={"quiz" = "\d+"})
     */
    public function newPartAction(Request $request, $quiz)
    {
        $imageService  = $this->get(ImageService::REFERENCE);
        $quiz          = $this->em()->getRepository('QuizzyBundle:Quiz')->find($quiz);

        $part = new Part();
        $part->setName($request->request->get('name'));
        $part->setQuiz($quiz);

        if ($request->request->get('desc') != null && $request->request->get('desc') != "") {
            $part->setDescription($request->request->get('desc'));
        } else {
            $part->setDescription(null);
        }

        if (!empty($request->request->get('media'))) {
            $media = new Media();
            $media->setPath($imageService->saveImage($request->request->get('media'), "_part_img.jpeg"));
            $part->setMedia($media);

            $this->em()->persist($media);
        }

        $this->em()->persist($part);
        $this->em()->flush();

        $res = [
            "id" => $part->getId(),
            "media" => $part->getMedia() != null ? $part->getMedia()->getPath() : null
        ];
        return new JsonResponse($res, 200);
    }

    /**
     * @Route("/part/edit/{part}", requirements={"part" = "\d+"})
     */
    public function setPartAction(Request $request, $part)
    {
        $imageService  = $this->get(ImageService::REFERENCE);
        $part          = $this->em()->getRepository('QuizzyBundle:Part')->find($part);

        if ($part->getMedia()) {// on delete l'image si il y a en
            $imageService->deleteImage($part->getMedia());
        }

        $part->setMedia(null);
        $part->setName($request->request->get('name'));

        if ($request->request->get('desc') != null && $request->request->get('desc') != "") {
            $part->setDescription($request->request->get('desc'));
        } else {
            $part->setDescription(null);
        }

        if (!empty($request->request->get('media'))) {
            $media = new Media();
            $media->setPath($imageService->saveImage($request->request->get('media'), "_part_img.jpeg"));
            $part->setMedia($media);

            $this->em()->persist($media);
        }

        $this->em()->persist($part);
        $this->em()->flush();

        $res = [
            "media" => $part->getMedia() != null ? $part->getMedia()->getPath() : null
        ];
        return new JsonResponse($res, 200);
    }

    private function em()
    {
        return $this->getDoctrine()->getEntityManager();
    }
}
