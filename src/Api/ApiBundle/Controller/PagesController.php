<?php
/**
 * Created by PhpStorm.
 * User: ODevS-Inc
 * Date: 10/17/2017
 * Time: 5:35 PM
 */

namespace Api\ApiBundle\Controller;

use Api\ApiBundle\Entity\Pages;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Get;

use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations



class PagesController extends Controller
{
	/**
	 * GET ALL PAGES
	 * @param Request $request
	 * @return JsonResponse
	 * @Rest\View()
     * @Rest\Get("/pages", name="pages_list")
     */
    public function getPagesAction(Request $request)
    {
    	//get Doctrine manager
        $em = $this->getDoctrine()->getManager();
        //find all pages objects
        $pages = $em->getRepository(Pages::class)->findAll();

      	return $pages;
    }


    /**
     * GET ON PAGE
     * @param Request $request
     * @return JsonResponse
     * @Rest\View()
     * @Rest\Get("/pages/{id}", name="page_one", requirements={"id"="\d+"})
     * 
     */
    public function getPageAction(Request $request)
    {
    	//get Doctrine manager
        $em = $this->getDoctrine()->getManager();
        //get page by id
        $page = $em->getRepository(Pages::class)->find($request->get('id'));

        //check if there is an object with the $id
         if (empty($page)) {
            return new JsonResponse(['message' => 'Page not found'], Response::HTTP_NOT_FOUND);
        }

        return $page;
    }

    /**
	 * CREATE PAGES
     * @param Request $request
     * @return JsonResponse
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/pages")
     */
    public function postPagesAction(Request $request)
    {
    	//instanciate new Page Object
    	$page = new Pages();
    	
    	//set Page Object attributes
    	$page->setTitle($request->get('title'));
		$page->setMetaTags($request->get('metaTags'));
		$page->setMetaDescription($request->get('metaDescription'));
    	$page->setPageType($request->get('pageType'));
    	$page->setPageLayout($request->get('pageLayout'));
    	$page->setSlug($request->get('slug'));
    	$page->setCountry($request->get('country'));
    	$page->setDate(new \DateTime('now'));
    	$page->setSoundbite($request->get('soundbit'));
    	$page->setDescription($request->get('description'));
    	$page->setAutoselect($request->get('autoSelect'));
    	$page->setUpdatedAt(new \DateTime('now'));
    

		//save Object into the DB
		$em = $this->getDoctrine()->getManager();
        $em->persist($page);
        $em->flush();

        return $page;

    }

    /**
     * DELETE ONE PAGE
     * @param Request $request
     * @return JsonResponse
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/pages/{id}")
     */
    public function removePageAction(Request $request)
    {
       //get Doctrine manager
        $em = $this->getDoctrine()->getManager();
        //get page by id
        $page = $em->getRepository(Pages::class)->find($request->get('id'));
        /* @var $place Place */

        if ($page) {
            $em->remove($page);
            $em->flush();
            return new JsonResponse(['message' => 'Page deletes succesfuly '], Response::HTTP_NOT_FOUND);
        }
        else{
        	return new JsonResponse(['message' => 'Page not found'], Response::HTTP_NOT_FOUND);
        }
    }

}