<?php


namespace App\Controller;


use App\Controller\Dto\AddCrewmateStation;
use App\Entity\CrewmateStations;
use App\Repository\CrewmateRepository;
use App\Repository\CrewmateStationsRepository;
use App\Repository\StationRepository;
use App\Singleton\LoggedCrewmate;
use App\Singleton\Validation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class StationController extends AbstractController implements TokenAuthenticatedInterface
{
    /**
     * @var StationRepository
     */
    private $stationRepository;
    /**
     * @var CrewmateRepository
     */
    private $crewmateRepository;
    /**
     * @var CrewmateStationsRepository
     */
    private $crewmateStations;
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * StationController constructor.
     * @param StationRepository $stationRepository
     * @param CrewmateRepository $crewmateRepository
     * @param CrewmateStationsRepository $crewmateStations
     * @param ValidatorInterface $validator
     */
    public function __construct(StationRepository $stationRepository, CrewmateRepository $crewmateRepository, CrewmateStationsRepository $crewmateStations, ValidatorInterface $validator)
    {
        $this->stationRepository = $stationRepository;
        $this->crewmateRepository = $crewmateRepository;
        $this->crewmateStations = $crewmateStations;
        $this->validator = $validator;
    }

    /**
     * @Route(name="stations", path="api/v1/stations", methods={"GET","OPTIONS","HEAD"})
     *
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        return new JsonResponse([
            'data' => $this->stationRepository->findAll()
        ]);
    }

    /**
     * @Route(name="addCrewmateStation", path="api/v1/crewmates/{crewmateId}/stations", methods={"POST","OPTIONS","HEAD"})
     *
     * @return JsonResponse
     */
    public function addMeNew(int $crewmateId, Request $request): JsonResponse
    {
        if (!LoggedCrewmate::getCrewmate()->isCaptain()) {
            return new JsonResponse([
                'code' => 'unauthorized'
            ], 401);
        }

        $message = json_decode($request->getContent() ?? '', true);

        $addStation = new AddCrewmateStation();
        $addStation->dateFrom = $message['from'] ?? null;
        $addStation->stationId =  $message['stationId'] ?? null;
        $addStation->crewmateId =  $crewmateId;

        $result = Validation::handle($this->validator->validate($addStation));

        if ($result ) {
            return $result;
        }

        $station = $this->stationRepository->find($addStation->stationId);
        $crewmate = $this->crewmateRepository->find($addStation->crewmateId);

        if($station === null || $crewmate === null) {
            return new JsonResponse(['code' => 'not-found'], 404);
        }

        $entity = new CrewmateStations();
        $entity->setCrewmate($crewmate);
        $entity->setStation($station);
        $entity->setDateFrom($addStation->dateFrom);

        $this->getDoctrine()->getManager()->persist($entity);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse([
            'data' => $entity
        ]);
    }
}