<?php


namespace App\Controller;


use App\Controller\Dto\CreateCrewmateDto;
use App\Controller\Dto\DepartmentDto;
use App\Controller\Dto\UpdateCrewmateDto;
use App\Domain\CrewmateService;
use App\Entity\Ship;
use App\Entity\ShipCrewmates;
use App\Repository\CrewmateRepository;
use App\Repository\ShipCrewmatesRepository;
use App\Repository\ShipRepository;
use App\Singleton\LoggedCrewmate;
use App\Singleton\Validation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
     * @var CrewmateService
     */
    private $crewmateService;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * CrewmateController constructor.
     * @param ShipCrewmatesRepository $shipCrewmateRepository
     * @param ShipRepository $shipRepository
     * @param CrewmateRepository $crewmateRepository
     * @param CrewmateService $crewmateService
     * @param ValidatorInterface $validator
     */
    public function __construct(ShipCrewmatesRepository $shipCrewmateRepository, ShipRepository $shipRepository, CrewmateRepository $crewmateRepository, CrewmateService $crewmateService, ValidatorInterface $validator)
    {
        $this->shipCrewmateRepository = $shipCrewmateRepository;
        $this->shipRepository = $shipRepository;
        $this->crewmateRepository = $crewmateRepository;
        $this->crewmateService = $crewmateService;
        $this->validator = $validator;
    }

    /**
     * @Route(name="crewmates", path="api/v1/ships/{shipId}/crewmates", methods={"GET","OPTIONS","HEAD"})
     * @param int $shipId
     * @return JsonResponse
     */
    public function getAll(int $shipId, Request $request): JsonResponse
    {

        $itemsPerPage = $request->query->get('itemsPerPage', null);
        $page = $request->query->get('page', null);

        return new JsonResponse(
            [
                'data' => array_map(function (ShipCrewmates $item) {
                    return $item->getCrewmate();
                }, $this->shipCrewmateRepository->findBy([
                    'ship' => $this->shipRepository->find($shipId)
                ], null, $itemsPerPage, $page * $itemsPerPage)),
                'meta' => [
                    'totalPages' => floor($this->shipCrewmateRepository->count([
                            'ship' => $this->shipRepository->find($shipId)
                        ]) / ($itemsPerPage ?: 1))
                ]
            ]
        );
    }

    /**
     * @Route(name="crewmate", path="api/v1/crewmates/{id}", methods={"GET","OPTIONS","HEAD"})
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

    /**
     * @Route(name="crewmateUpdate", path="api/v1/crewmates/{id}", methods={"PUT","OPTIONS","HEAD"})
     * @param int $id
     * @return JsonResponse
     */
    public function replace(int $id, Request $request): Response
    {
        $message = json_decode($request->getContent() ?? '', true);

        $dto = new UpdateCrewmateDto();
        $dto->name = $message['name'] ?? null;
        $dto->lastName = $message['lastName'] ?? null;
        $dto->departmentId = $message['departmentId'] ?? null;
        $dto->shipId = $message['shipId'] ?? null;
        $dto->experience = $message['experience'] ?? null;
        $dto->intelligence = $message['intelligence'] ?? null;
        $dto->physicalCondition = $message['physicalCondition'] ?? null;
        $dto->strength = $message['strength'] ?? null;
        $dto->dexterity = $message['dexterity'] ?? null;

        $user = LoggedCrewmate::getCrewmate();

        if (!$user->isCaptain()) {
            return new JsonResponse([
                'code' => 'unauthorized'
            ], 401);
        }

        $result = Validation::handle($this->validator->validate($dto));

        if ($result) {
            return $result;
        }

        return $this->crewmateService->update($id, $dto) ?: new Response(null, 204);
    }

    /**
     * @Route(name="crewmateCreate", path="api/v1/crewmates", methods={"POST","OPTIONS","HEAD"})
     * @param int $id
     * @return JsonResponse
     */
    public function create(Request $request): Response
    {
        $message = json_decode($request->getContent() ?? '', true);

        $dto = new CreateCrewmateDto();
        $dto->name = $message['name'] ?? null;
        $dto->lastName = $message['lastName'] ?? null;
        $dto->departmentId = $message['departmentId'] ?? null;
        $dto->shipId = $message['shipId'] ?? null;
        $dto->experience = $message['experience'] ?? null;
        $dto->intelligence = $message['intelligence'] ?? null;
        $dto->physicalCondition = $message['physicalCondition'] ?? null;
        $dto->strength = $message['strength'] ?? null;
        $dto->dexterity = $message['dexterity'] ?? null;
        $dto->password = $message['password'] ?? null;
        $dto->login = $message['login'] ?? null;

        $result = Validation::handle($this->validator->validate($dto));

        if ($result) {
            return $result;
        }

        return $this->crewmateService->create($dto) ?: new Response(null, 204);
    }

    /**
     * @Route(name="deleteCrewmate", path="api/v1/crewmates/{id}", methods={"DELETE","OPTIONS","HEAD"})
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

        $crewmate = $this->crewmateRepository->find($id);

        if ($crewmate === null) {
            return new JsonResponse([
                'code' => 'bad station'
            ], 400);
        }

        $this->getDoctrine()->getManager()->remove($crewmate);
        $this->getDoctrine()->getManager()->flush();

        return new Response(null, 204);
    }


    /**
     * @Route(name="me", path="api/v1/auth/me", methods={"GET","OPTIONS","HEAD"})
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        $crewmate = LoggedCrewmate::getCrewmate();

        if (empty($crewmate)) {
            return new JsonResponse(['code' => 'unauthorized'], 401);
        }

        return new JsonResponse(
            [
                'data' => $crewmate
            ]
        );
    }

}