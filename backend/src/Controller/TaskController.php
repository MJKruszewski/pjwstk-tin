<?php


namespace App\Controller;


use App\Controller\Dto\AddCrewmateStation;
use App\Controller\Dto\TaskDto;
use App\Repository\CrewmateRepository;
use App\Repository\CrewmateStationsRepository;
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
     * @param TaskRepository $taskRepository
     * @param ValidatorInterface $validator
     */
    public function __construct(StationRepository $stationRepository, CrewmateRepository $crewmateRepository, CrewmateStationsRepository $crewmateStations, TaskRepository $taskRepository, ValidatorInterface $validator)
    {
        $this->stationRepository = $stationRepository;
        $this->crewmateRepository = $crewmateRepository;
        $this->crewmateStations = $crewmateStations;
        $this->taskRepository = $taskRepository;
        $this->validator = $validator;
    }

    /**
     * @Route(name="tasks", path="api/v1/tasks", methods={"GET","OPTIONS","HEAD"})
     *
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        return new JsonResponse([
            'data' => $this->taskRepository->findAll()
        ]);
    }

    /**
     * @Route(name="tasksAssignCrewmate", path="api/v1/tasks/{id}", methods={"PATCH","OPTIONS","HEAD"})
     *
     * @return JsonResponse
     */
    public function assignCrewmate(int $id, Request $request): Response
    {
        if (!LoggedCrewmate::getCrewmate()->isCaptain()) {
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

        $entity = $this->taskRepository->find($id);

        if($entity === null) {
            return new JsonResponse(['code' => 'not-found'], 404);
        }

        if (
            ($task->status && $entity->getAssignee() !== null && $entity->getAssignee() === LoggedCrewmate::getCrewmate()->getId())
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