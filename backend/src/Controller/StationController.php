<?php


namespace App\Controller;


use App\Controller\Dto\AddCrewmateStation;
use App\Controller\Dto\AddTaskDto;
use App\Controller\Dto\StationDto;
use App\Domain\StationService;
use App\Entity\CrewmateStations;
use App\Entity\Station;
use App\Entity\Task;
use App\Repository\CrewmateRepository;
use App\Repository\CrewmateStationsRepository;
use App\Repository\DepartmentRepository;
use App\Repository\StationRepository;
use App\Singleton\LoggedCrewmate;
use App\Singleton\Validation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @var StationService
     */
    private $stationService;
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * StationController constructor.
     * @param StationRepository $stationRepository
     * @param CrewmateRepository $crewmateRepository
     * @param StationService $stationService
     * @param ValidatorInterface $validator
     */
    public function __construct(StationRepository $stationRepository, CrewmateRepository $crewmateRepository, StationService $stationService, ValidatorInterface $validator)
    {
        $this->stationRepository = $stationRepository;
        $this->crewmateRepository = $crewmateRepository;
        $this->stationService = $stationService;
        $this->validator = $validator;
    }


    /**
     * @Route(name="stations", path="api/v1/stations", methods={"GET","OPTIONS","HEAD"})
     *
     * @return JsonResponse
     */
    public function getAll(Request $request): JsonResponse
    {
        $itemsPerPage = $request->query->get('itemsPerPage', null);
        $page = $request->query->get('page', null);

        return new JsonResponse([
            'data' => $this->stationRepository->findBy([], null, $itemsPerPage, $page * $itemsPerPage),
            'meta' => [
                'totalPages' => floor($this->stationRepository->count([]) / ($itemsPerPage ?: 1))
            ]
        ]);
    }

    /**
     * @Route(name="updateStation", path="api/v1/stations/{id}", methods={"PATCH","OPTIONS","HEAD"})
     *
     * @return JsonResponse
     */
    public function update(int $id, Request $request): Response
    {
        $message = json_decode($request->getContent() ?? '', true);

        $dto = new StationDto();
        $dto->code = $message['code'] ?? null;
        $dto->departmentId = $message['departmentId'] ?? null;

        $user = LoggedCrewmate::getCrewmate();

        if (!$user->isCaptain()) {
            return new JsonResponse([
                'code' => 'unauthorized'
            ], 401);
        }

        $result = Validation::handle($this->validator->validate($dto));

        if ($result ) {
            return $result;
        }

        return $this->stationService->update($id, $dto) ?: new Response(null, 204);
    }

    /**
     * @Route(name="deleteStation", path="api/v1/stations/{id}", methods={"DELETE","OPTIONS","HEAD"})
     *
     * @return JsonResponse
     */
    public function delete(int $id): Response {
        $user = LoggedCrewmate::getCrewmate();

        if (!$user->isCaptain()) {
            return new JsonResponse([
                'code' => 'unauthorized'
            ], 401);
        }

        $station = $this->stationRepository->find($id);

        if ($station === null) {
            return new JsonResponse([
                'code' => 'bad station'
            ], 400);
        }

        $this->getDoctrine()->getManager()->remove($station);
        $this->getDoctrine()->getManager()->flush();

        return new Response(null, 204);
    }

    /**
     * @Route(name="createStation", path="api/v1/stations", methods={"POST","OPTIONS","HEAD"})
     *
     * @return JsonResponse
     */
    public function create(Request $request): Response
    {
        $message = json_decode($request->getContent() ?? '', true);

        $dto = new StationDto();
        $dto->code = $message['code'] ?? null;
        $dto->departmentId = $message['departmentId'] ?? null;

        $user = LoggedCrewmate::getCrewmate();

        if (!$user->isCaptain()) {
            return new JsonResponse([
                'code' => 'unauthorized'
            ], 401);
        }

        $result = Validation::handle($this->validator->validate($dto));

        if ($result ) {
            return $result;
        }

        return $this->stationService->create($dto) ?: new Response(null, 204);
    }

    /**
     * @Route(name="addCrewmateStation", path="api/v1/crewmates/{crewmateId}/stations", methods={"POST","OPTIONS","HEAD"})
     *
     * @return JsonResponse
     */
    public function addMeNew(int $crewmateId, Request $request): Response
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

        return $this->stationService->assignToStation($addStation) ?: new Response(null, 204);
    }

    /**
     * @Route(name="crewmateStationResign", path="api/v1/crewmates/{crewmateId}/stations/{stationId}", methods={"POST","OPTIONS","HEAD"})
     *
     * @return JsonResponse
     */
    public function resign(int $crewmateId, int $stationId): Response
    {
        if (!LoggedCrewmate::getCrewmate()->isCaptain()) {
            return new JsonResponse([
                'code' => 'unauthorized'
            ], 401);
        }

        $crewmate = $this->crewmateRepository->find($crewmateId);

        if ($crewmate === null) {
            return new JsonResponse([
                'code' => 'not-found'
            ], 404);
        }

        $found = null;

        foreach ($crewmate->getCrewmateStations() as $station) {
            if ($station->getStation()->getId() === $stationId) {
                $station->setDateTo(new \DateTime());
                $found = $station;
                break;
            }
        }

        if ($found === null) {
            return new JsonResponse([
                'code' => 'not-found'
            ], 404);
        }

        $this->getDoctrine()->getManager()->persist($found);
        $this->getDoctrine()->getManager()->flush();

        return new Response(null, 204);
    }
}