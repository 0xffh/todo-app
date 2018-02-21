<?php

namespace AppBundle\Controller\Api;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AuthController extends Controller
{
    /**
     * @ApiDoc(
     *     section="Sign in",
     *     description="Login to get jwt token",
     *     requirements={
     *      {"name"="_username", "dataType"="string", "requirement"="*", "description"="Username or email"},
     *      {"name"="_password", "dataType"="string", "requirement"="*", "description"="Password"}
     *     }
     * )
     *
     * @Route("login_check", name="login_check", methods={"POST"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction()
    {
    }

}
