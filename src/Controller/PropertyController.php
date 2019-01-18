<?php

namespace App\Controller;


use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;
    /**
     * PropertyController constructor.
     * @param PropertyRepository $repository
     * @param ObjectManager $em
     */
    public function __construct( PropertyRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/biens",name="property.index")
     * @return Response
     */

    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $search = new PropertySearch();
        $form= $this->createForm(PropertySearchType::class,$search);
        $form->handleRequest($request);
        $property = $paginator->paginate(
            $this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1)/*page number*/,
            3/*limit per page*/) ;

       return new Response($this->renderView('property/index.html.twig',[
           'current_menu' => 'properties',
           'properties' => $property,
           'form'=>$form->createView()
       ]));
    }

    /**
     * @Route ("/biens/{slug}.{id}", name="property.show" , requirements={"slug":"[0-9a-z\-]*"})
     * @return Response
     */
    public function show(Property $property , string $slug):Response
    {
        if($property->getSlug() !== $slug )
        {
           return $this->redirectToRoute('property.show',[
                'id' => $property->getId(),
                'slug' => $property->getSlug()
            ], 301);
        }
//        $property = $this->repository->find($id);
        return $this->render('property/show.html.twig',[
            'property'=> $property,
            'current_menu' => 'properties'
        ]);
    }
}