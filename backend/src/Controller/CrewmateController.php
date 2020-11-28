<?php


namespace App\Controller;


use App\Entity\Ship;
use App\Entity\ShipCrewmates;
use App\Repository\CrewmateRepository;
use App\Repository\ShipCrewmatesRepository;
use App\Repository\ShipRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CrewmateController extends AbstractController implements TokenAuthenticatedInterface
{
    /**
     * @var ShipCrewmatesRepository
     */
    private $shipCrewmateRepository;

    /**
     * @var ShipRepository
     */
    private $shipRepository;

    /**
     * @var CrewmateRepository
     */
    private $crewmateRepository;

    /**
     * CrewmateController constructor.
     * @param ShipCrewmatesRepository $shipCrewmateRepository
     * @param ShipRepository $shipRepository
     * @param CrewmateRepository $crewmateRepository
     */
    public function __construct(ShipCrewmatesRepository $shipCrewmateRepository, ShipRepository $shipRepository, CrewmateRepository $crewmateRepository)
    {
        $this->shipCrewmateRepository = $shipCrewmateRepository;
        $this->shipRepository = $shipRepository;
        $this->crewmateRepository = $crewmateRepository;
    }

    /**
     * @Route(name="crewmates", path="api/v1/ships/{shipId}/crewmates")
     * @param int $shipId
     * @return JsonResponse
     */
    public function getAll(int $shipId): JsonResponse
    {
        return new JsonResponse(
            [
                'data' => array_map(function (ShipCrewmates $item) {
                    return $item->getCrewmate();
                }, $this->shipCrewmateRepository->findBy([
                    'ship' => $this->shipRepository->find($shipId)
                ]))
            ]
        );
    }

    /**
     * @Route(name="crewmate", path="api/v1/crewmates/{id}")
     * @param int $id
     * @return JsonResponse
     */
    public function getOne(int $id): JsonResponse
    {
        return new JsonResponse(
            [
                'data' => $this->crewmateRepository->find($id)
            ]
        );
    }



}