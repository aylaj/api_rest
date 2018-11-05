<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations\Get; //pour annotation de la route avec FOSRestBundle
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;

class UserController extends Controller
{
    /**
     * @Get("/users")
     *
     */
    public function getUsersAction(Request $request)
    {
      $em=$this->getDoctrine()->getEntityManager();
        $users = $em->getRepository('AppBundle:User')->findAll();

        $formatted = array();
        foreach ($users as $user) {
            $formatted[] =array('id' => $user->getId(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'email' => $user->getEmail());
        }

        return new JsonResponse($formatted);
    }

    /**
     *@Get("/users/{id}")
     *
     */

    public Function getUserAction(Request $request)
    {
      $em=$this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('AppBundle:User')->find($request->get('id'));
        if (empty($user)) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        $formatted = array('id' => $user->getId(),
                            'firstname' => $user->getFirstname(),
                            'lastname' => $user->getLastname(),
                            'email' => $user->getEmail());

       return new JsonResponse($formatted);



    }
}
