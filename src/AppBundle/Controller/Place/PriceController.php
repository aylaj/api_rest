<?php
namespace AppBundle\Controller\Place;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use AppBundle\Form\PriceType;
use AppBundle\Entity\Price;
use FOS\RestBundle\Controller\Annotations\View;

class PriceController extends Controller
{
  /**
    * @Rest\View(serializerGroups={"price"})
    * @Rest\Get("/places/{id}/prices")
    */

   public function getPricesAction(Request $request)
    {
      $em=$this->getDoctrine()->getEntityManager();
      $place=$em->getRepository("AppBundle:Place")->find($request->get('id'));
      if(empty($place)){
        return $this->placeNotFound();
      }
      return $place->getPrices();

    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"price"})
     * @Rest\Post("/places/{id}/prices")
     */
    public function postPricesAction(Request $request)
    {
      $em=$this->getDoctrine()->getEntityManager();
      $place=$em->getRepository("AppBundle:Place")->find($request->get('id'));
      if(empty($place)){
        return $this->placeNotFound();
      }

      $price=new Price();
      $price->setPlace($place); // Ici, le lieu est associé au prix
      $form = $this->createForm(PriceType::class, $price);

      $form->submit($request->request->all(),false);
        if ($form->isValid()) {

            $em->persist($price);
            $em->flush();
            return $price;
        } else {
            return $form;
        }

    }

    private function placeNotFound(){
      return \FOS\RestBundle\View\View::create(['message' => 'Place not found'], Response::HTTP_NOT_FOUND);
    }
}
