<?php

namespace AppBundle\Controller\Api;

use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use AppBundle\Util\Helper;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends Controller
{
    /**
     * @ApiDoc(
     *     section="Users",
     *     description="User registration",
     *     input={"class": UserType::class}
     * )
     *
     * @Route("/users", name="user_create", methods={"POST"})
     *
     * @param UserRepository $userRepository
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param SerializerInterface          $serializer
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createUserAction(
        UserRepository $userRepository,
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        SerializerInterface $serializer
    ) {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $userRepository->save($user);

            return JsonResponse::fromJsonString($serializer->serialize($user, 'json'), Response::HTTP_CREATED);
        }

        return JsonResponse::create(Helper::getErrorsFromForm($form), Response::HTTP_BAD_REQUEST);
    }
}