<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations\Get; //pour annotation de la route avec FOSRestBundle

use AppBundle\Entity\Place;


class PlaceController extends Controller
{

  /**
   *@Get("/places")
   *
   */

   public Function getPlacesAction(Request $request)
   {
     $em=$this->getDoctrine()->getEntityManager();
     $places=$em->getRepository('AppBundle:Place')->findAll();
     /* @var $places Place[] */

     foreach($places as $place){
       $formatted[]=array('id'=>$place->getId(),'name'=>$place->getName(),'address'=>$place->getAddress());

     }

    return new JsonResponse($formatted);

  }
  /**
   *@Get("/places/{id}")
   *
   */

   public function getPlaceAction(Request $request)
   {
     //var_dump($request->get('id'));
     $em=$this->getDoctrine()->getEntityManager();
     $place=$em->getRepository('AppBundle:Place')->find($request->get('id'));
      /* @var $place Place */
     if(empty($place)){
       //return new JsonResponse(['message' => 'Place not found'], Response::HTTP_NOT_FOUND);

       throw new NotFoundHttpException(' 	Ressource non trouvée');
     }
     $formatted[]=array('id'=>$place->getId(),'name'=>$place->getName(),'address'=>$place->getAddress());

    return new JsonResponse($formatted);

   }





}


















 ?>
