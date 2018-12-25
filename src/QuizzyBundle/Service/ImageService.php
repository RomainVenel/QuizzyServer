<?php

namespace QuizzyBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Doctrine\ORM\EntityManager;
use QuizzyBundle\Entity\Media;

class ImageService
{
    const REFERENCE = "quizzy.image_service";

    private $container;
    protected $em;

    public function __construct(Container $container, EntityManager $entityManager) {
        $this->container = $container;
        $this->em        = $entityManager;
    }

    /*
     * Decode et sauvegarde l'image dans le dossier upload/image
     * Retourne le path de l'image
    */
    public function saveImage($base64, $name)
    {
        $imageBase64 = base64_decode($base64);
        $nameFile = "upload/image/" . uniqid() . $name;
        file_put_contents(
            $this->container->get('kernel')->getRootDir() . "/../web/" . $nameFile,
            $imageBase64
        );

        return $nameFile;
    }

    /*
     * Supprimer une image
    */
    public function deleteImage(Media $media)
    {
        if (file_exists($this->container->getParameter('kernel.project_dir') . "/web/". $media->getPath())) {
            unlink(
                $this->container->getParameter('kernel.project_dir') . "/web/". $media->getPath()
            );
        }
        $this->em->remove($media);   
    }
}
