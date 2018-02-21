<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Task;
use AppBundle\Form\TaskType;
use AppBundle\Repository\TaskRepository;
use AppBundle\Util\Helper;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class TaskController extends Controller
{
    /**
     * @ApiDoc(
     *     section="Tasks",
     *     description="Get list tasks logged user",
     *     headers={
     *         {"name": "Authorization", "required": true, "description": "Authorization key Bearer {token}"}
     *     }
     * )
     *
     * @Route("/tasks", name="task_list", methods={"GET"})
     *
     * @param TaskRepository      $taskRepository
     * @param SerializerInterface $serializer
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAllTasksAction(TaskRepository $taskRepository, SerializerInterface $serializer)
    {
        $tasks = $taskRepository->findBy([
            'user' => $this->getUser(),
        ]);

        return JsonResponse::fromJsonString($serializer->serialize($tasks, 'json'));
    }

    /**
     * @ApiDoc(
     *     section="Tasks",
     *     description="Get task logged user by id",
     *     headers={
     *         {"name": "Authorization", "required": true, "description": "Authorization key Bearer {token}"}
     *     }
     * )
     *
     * @Route("/tasks/{id}", name="task_show_one", methods={"GET"})
     *
     * @ParamConverter("task", class="AppBundle:Task", converter="logged_user_task_converter")
     *
     * @param Task                $task
     * @param SerializerInterface $serializer
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getOneTasksAction(Task $task, SerializerInterface $serializer)
    {
        return JsonResponse::fromJsonString($serializer->serialize($task, 'json'));
    }

    /**
     * @ApiDoc(
     *     section="Tasks",
     *     description="Create new task",
     *     input={"class": TaskType::class},
     *     headers={
     *         {"name": "Authorization", "required": true, "description": "Authorization key Bearer {token}"}
     *     }
     * )
     *
     * @Route("/tasks", name="task_create", methods={"POST"})
     *
     * @param TaskRepository      $taskRepository
     * @param Request             $request
     * @param SerializerInterface $serializer
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createTaskAction(TaskRepository $taskRepository, Request $request, SerializerInterface $serializer)
    {
        $task = new Task($this->getUser());

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taskRepository->save($task);

            return JsonResponse::fromJsonString($serializer->serialize($task, 'json'), Response::HTTP_CREATED);
        }

        return JsonResponse::create(Helper::getErrorsFromForm($form), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @ApiDoc(
     *     section="Tasks",
     *     description="Delete task logged user by id",
     *     headers={
     *         {"name": "Authorization", "required": true, "description": "Authorization key Bearer {token}"}
     *     }
     * )
     *
     * @Route("/tasks/{id}", name="task_delete", methods={"DELETE"})
     *
     * @ParamConverter("task", class="AppBundle:Task", converter="logged_user_task_converter")
     *
     * @param Task                $task
     * @param TaskRepository      $taskRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteTaskAction(Task $task, TaskRepository $taskRepository)
    {
        $taskRepository->remove($task);

        return JsonResponse::create(['deleted' => true]);
    }

    /**
     * @ApiDoc(
     *     section="Tasks",
     *     description="Update existing task by id",
     *     input={"class": TaskType::class, "name": "task"},
     *     headers={
     *         {"name": "Authorization", "required": true, "description": "Authorization key Bearer {token}"}
     *     }
     * )
     *
     * @Route("/tasks/{id}", name="task_put", methods={"PUT"})
     *
     * @ParamConverter("task", class="AppBundle:Task", converter="logged_user_task_converter")
     *
     * @param Task $task
     * @param TaskRepository      $taskRepository
     * @param Request             $request
     * @param SerializerInterface $serializer
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function putTaskAction(Task $task, TaskRepository $taskRepository, Request $request, SerializerInterface $serializer)
    {
        if (!$request->request->has('task')) {
            throw new BadRequestHttpException('`task` form name allowed!');
        }

        $form = $this->createForm(TaskType::class, $task);

        $form->submit($request->request->get('task'));

        if ($form->isSubmitted() && $form->isValid()) {
            $taskRepository->flush();

            return JsonResponse::fromJsonString($serializer->serialize($task, 'json'));
        }

        return JsonResponse::create(Helper::getErrorsFromForm($form));
    }

    /**
     * @ApiDoc(
     *     section="Tasks",
     *     description="Change isCompleted task state by id",
     *     headers={
     *         {"name": "Authorization", "required": true, "description": "Authorization key Bearer {token}"}
     *     }
     * )
     *
     * @Route("/tasks/{id}/change-state", name="task_change_state", methods={"POST"})
     *
     * @ParamConverter("task", class="AppBundle:Task", converter="logged_user_task_converter")
     *
     * @param Task                $task
     * @param TaskRepository      $taskRepository
     * @param SerializerInterface $serializer
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function changeStateTaskAction(Task $task, TaskRepository $taskRepository, SerializerInterface $serializer)
    {
        if ($task->isCompleted()) {
            $task->setIsCompletedFalse();
        } else {
            $task->setIsCompletedTrue();
        }

        $taskRepository->flush();

        return JsonResponse::fromJsonString($serializer->serialize($task, 'json'));
    }
}
