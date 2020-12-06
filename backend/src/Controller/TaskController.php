<?php


namespace App\Controller;


use App\Controller\Dto\AddCrewmateStation;
use App\Controller\Dto\AddTaskDto;
use App\Controller\Dto\TaskDto;
use App\Entity\Task;
use App\Repository\CrewmateRepository;
use App\Repository\CrewmateStationsRepository;
use App\Repository\ShipRepository;
use App\Repository\StationRepository;
use App\Repository\TaskRepository;
use App\Singleton\LoggedCrewmate;
use App\Singleton\Validation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaskController extends AbstractController implements TokenAuthenticatedInterface
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
     * @var ShipRepository
     */
    private $shipRepository;
    /**
     * @var TaskRepository
     */
    private $taskRepository;
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * TaskController constructor.
     * @param StationRepository $stationRepository
     * @param CrewmateRepository $crewmateRepository
     * @param CrewmateStationsRepository $crewmateStations
     * @param ShipRepository $shipRepository
     * @param TaskRepository $taskRepository
     * @param ValidatorInterface $validator
     */
    public function __construct(StationRepository $stationRepository, CrewmateRepository $crewmateRepository, CrewmateStationsRepository $crewmateStations, ShipRepository $shipRepository, TaskRepository $taskRepository, ValidatorInterface $validator)
    {
        $this->stationRepository = $stationRepository;
        $this->crewmateRepository = $crewmateRepository;
        $this->crewmateStations = $crewmateStations;
        $this->shipRepository = $shipRepository;
        $this->taskRepository = $taskRepository;
        $this->validator = $validator;
    }

    /**
     * @Route(name="tasks", path="api/v1/ships/{id}/tasks", methods={"GET","OPTIONS","HEAD"})
     *
     * @return JsonResponse
     */
    public function getAllShip(int $id, Request $request): JsonResponse
    {
        $ship = $this->shipRepository->find($id);
        $loggedUser = LoggedCrewmate::getCrewmate();

        if (!$loggedUser->isCaptain() && $loggedUser->getShip()->getId() !== $id) {
            return new JsonResponse([
                'code' => 'unauthorized'
            ], 401);
        }

        $itemsPerPage = $request->query->get('itemsPerPage', null);
        $page = $request->query->get('page', null);

        return new JsonResponse([
            'data' => $this->taskRepository->findBy(['ship' => $ship], null, $itemsPerPage, $page * $itemsPerPage),
            'meta' => [
                'totalPages' => floor($this->taskRepository->count(['ship' => $ship]) / ($itemsPerPage ?: 1))
            ]
        ]);
    }

    /**
     * @Route(name="saveTask", path="api/v1/tasks", methods={"POST","OPTIONS","HEAD"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function saveTask(Request $request): Response
    {
        $message = json_decode($request->getContent() ?? '', true);

        $dto = new AddTaskDto();
        $dto->code = $message['code'] ?? null;
        $dto->shipId = $message['shipId'] ?? null;
        $dto->stationId = $message['stationId'] ?? null;
        $dto->priority = $message['priority'] ?? null;

        $user = LoggedCrewmate::getCrewmate();

        if ($dto->shipId !== null && !$user->isCaptain()) {
            return new JsonResponse([
                'code' => 'unauthorized'
            ], 401);
        }

        $result = Validation::handle($this->validator->validate($dto));

        if ($result ) {
            return $result;
        }

        if ($dto->shipId === null) {
            $dto->shipId = $user->getShip()->getId();
        }

        $task = new Task();
        $task
            ->setStatus(Task::STATUS_NEW)
            ->setShip($this->shipRepository->find($dto->shipId))
            ->setPriority($dto->priority)
            ->setCode($dto->code)
            ->setCreatedAt(new \DateTime())
            ->setReporter($user);

        $station = $this->stationRepository->find($dto->stationId);

        if ($station !== null) {
            $task->setStation($station);
        }

        $this->getDoctrine()->getManager()->persist($task);
        $this->getDoctrine()->getManager()->flush();

        return new Response(null, 204);
    }

    /**
     * @Route(name="tasksAssignCrewmate", path="api/v1/tasks/{id}", methods={"PATCH","OPTIONS","HEAD"})
     *
     * @return JsonResponse
     */
    public function assignCrewmate(int $id, Request $request): Response
    {
        $entity = $this->taskRepository->find($id);

        if ($entity === null) {
            return new JsonResponse(['code' => 'not-found'], 404);
        }

        if (!LoggedCrewmate::getCrewmate()->isCaptain() && $entity->getShip()->getId() !== LoggedCrewmate::getCrewmate()->getShip()->getId()) {
            return new JsonResponse([
                'code' => 'unauthorized'
            ], 401);
        }

        $message = json_decode($request->getContent() ?? '', true);

        $task = new TaskDto();
        $task->assignee = $message['assigneeId'] ?? null;
        $task->status = $message['status'] ?? null;

        $result = Validation::handle($this->validator->validate($task));

        if ($result ) {
            return $result;
        }

        if (
            ($task->status && $entity->getAssignee() !== null && $entity->getAssignee()->getId() === LoggedCrewmate::getCrewmate()->getId())
            || ($task->status && LoggedCrewmate::getCrewmate()->isCaptain())
        ) {
            $entity->setStatus($task->status);
        }

        if (
            ($task->assignee && $task->assignee === LoggedCrewmate::getCrewmate()->getId() && $entity->getAssignee() === null)
            || ($task->assignee && LoggedCrewmate::getCrewmate()->isCaptain())
        ) {
            $crewmate = $this->crewmateRepository->find($task->assignee);

            if($crewmate === null) {
                return new JsonResponse(['code' => 'not-found'], 404);
            }

            $entity->setAssignee($crewmate);
        }

        $this->getDoctrine()->getManager()->persist($entity);
        $this->getDoctrine()->getManager()->flush();

        return new Response(null, 204);
    }

}