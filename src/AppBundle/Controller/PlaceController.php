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
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle
use AppBundle\Form\PlaceType;
use AppBundle\Entity\Place;


class PlaceController extends Controller
{

  /**
   *@Rest\View(serializerGroups={"place"})
   *@Rest\Get("/places")
   *
   */

   public Function getPlacesAction(Request $request)
   {
     $em=$this->getDoctrine()->getEntityManager();
     $places=$em->getRepository('AppBundle:Place')->findAll();


    /* foreach($places as $place){
       $formatted[]=array('id'=>$place->getId(),'name'=>$place->getName(),'address'=>$place->getAddress());

     }*/
    // $viewHandler=$this->get('fos_rest.view_handler');
     /*$view=View::create($places);
     $view->setFormat('json');*/

    return $places;

  }
  /**
   *@Rest\View(serializerGroups={"place"})
   *@Rest\Get("/places/{id}")
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
    return $place;

   }

   /**
    *@Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"place"})
    *@Rest\Post("/places")
    */
    public function postPlacesAction(Request $request)
    {
      $place=new Place();
      $form=$this->createForm(PlaceType::class, $place);
      $form->submit($request->request->all()); // Validation des données
      if ($form->isSubmitted() && $form->isValid()){
        $em=$this->getDoctrine()->getEntityManager();
        $em->persist($place);
        $em->flush();
        return $place;
      }
      else {
        return $form;
      }
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT, serializerGroups={"place"})
     * @Rest\Delete("/places/{id}")
     */
    public function removePlaceAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $place = $em->getRepository('AppBundle:Place')
                    ->find($request->get('id'));
        $prices=$place->getPrices();
        foreach($prices as $price){
          $em->remove($price);

        }
        if($place){
          $em->remove($place);

          $em->flush();

        }

    }

    /**
     * @Rest\View(serializerGroups={"place"})
     * @Rest\Put("/places/{id}")
     */

   public function updatePlaceAction(Request $request)
   {
     $em=$this->getDoctrine()->getEntityManager();
     $place = $em->getRepository('AppBundle:Place')->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire


        if (empty($place)) {
          //  return new JsonResponse(['message' => 'Place not found'], Response::HTTP_NOT_FOUND);
            return \FOS\RestBundle\View\View::create(['message' => 'Place not found'], Response::HTTP_NOT_FOUND);

        }

        $form = $this->createForm(PlaceType::class, $place);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($place);
            $em->flush();
            return $place;
        } else {
            return $form;
        }

   }
   /**
     * @Rest\View(serializerGroups={"place"})
     * @Rest\Patch("/places/{id}")
     */
    public function patchPlaceAction(Request $request)
    {
        $place = $this->getDoctrine()->getEntityManager()
                ->getRepository('AppBundle:Place')
                ->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire

        if (empty($place)) {
            return new JsonResponse(['message' => 'Place not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(PlaceType::class, $place);

         // Le paramètre false dit à Symfony de garder les valeurs dans notre
         // entité si l'utilisateur n'en fournit pas une dans sa requête
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($place);
            $em->flush();
            return $place;
        } else {
            return $form;
        }
    }








}


















 ?>
