<?php


namespace App\Controller;


use App\Entity\Ship;
use App\Repository\ShipRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ShipController extends AbstractController implements TokenAuthenticatedInterface
{

    /**
     * @var ShipRepository
     */
    private $shipRepository;

    /**
     * ShipController constructor.
     * @param ShipRepository $shipRepository
     */
    public function __construct(ShipRepository $shipRepository)
    {
        $this->shipRepository = $shipRepository;
    }

    /**
     * @Route(name="ships", path="api/v1/ships", methods={"GET","OPTIONS","HEAD"})
     */
    public function getAll(): JsonResponse
    {
        return new JsonResponse([
            'data' => $this->shipRepository->findAll()
        ]);
    }

}