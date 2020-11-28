<?php


namespace App\Controller;


use App\Repository\DepartmentRepository;
use App\Repository\StationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class StationController extends AbstractController implements TokenAuthenticatedInterface
{
    /**
     * @var StationRepository
     */
    private $stationRepository;

    /**
     * StationController constructor.
     * @param StationRepository $stationRepository
     */
    public function __construct(StationRepository $stationRepository)
    {
        $this->stationRepository = $stationRepository;
    }


    /**
     * @Route(name="stations", path="api/v1/stations")
     *
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        return new JsonResponse([
            'data' => $this->stationRepository->findAll()
        ]);
    }
}