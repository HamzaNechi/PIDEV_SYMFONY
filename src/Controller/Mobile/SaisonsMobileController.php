<?php
namespace App\Controller\Mobile;

use App\Entity\Saisons;
use App\Repository\SaisonRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mobile/saisons")
 */
class SaisonsMobileController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function index(SaisonRepository $saisonsRepository): Response
    {
        $saisonss = $saisonsRepository->findAll();

        if ($saisonss) {
            return new JsonResponse($saisonss, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }
}
