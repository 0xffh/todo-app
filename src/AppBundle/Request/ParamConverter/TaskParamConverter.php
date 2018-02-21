<?php

namespace AppBundle\Request\ParamConverter;


use AppBundle\Entity\Task;
use AppBundle\Repository\TaskRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TaskParamConverter implements ParamConverterInterface
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    /**
     * @var TaskRepository
     */
    private $taskRepository;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @param ManagerRegistry       $registry
     * @param TaskRepository        $taskRepository
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        ManagerRegistry $registry = null,
        TaskRepository $taskRepository,
        TokenStorageInterface $tokenStorage
    ) {
        $this->registry       = $registry;
        $this->taskRepository = $taskRepository;
        $this->tokenStorage   = $tokenStorage;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $id = (int)$request->attributes->get('id');

        $task = $this->taskRepository->findOneBy(
            [
                'id'   => $id,
                'user' => $this->tokenStorage->getToken()->getUser(),
            ]
        );

        if ($task === null || ! ($task instanceof Task)) {
            throw new NotFoundHttpException(
                sprintf('%s object not found.', $configuration->getClass())
            );
        }

        $request->attributes->set($configuration->getName(), $task);
    }

    public function supports(ParamConverter $configuration)
    {
        if ($this->registry === null || ! count($this->registry->getManagers())) {
            return false;
        }

        if ($configuration->getClass() === null) {
            return false;
        }

        $em = $this->registry->getManagerForClass($configuration->getClass());

        if ($em === null) {
            return false;
        }

        if (Task::class !== $em->getClassMetadata($configuration->getClass())->getName()) {
            return false;
        }

        return true;
    }

}