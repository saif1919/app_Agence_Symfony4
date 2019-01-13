<?php

namespace App\Controller;


use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    public function index(): Response
    {
        $property = new Property();
//        $property->setTitle('Mon premier')
//            ->setPrice(200000)
//            ->setRooms(4)
//            ->setBedrooms(3)
//            ->setDescription('une petite description')
//            ->setSurface(60)
//            ->setFloor(4)
//            ->setHeat(1)
//            ->setCity('Tunis')
//            ->setAddress('oued ellil')
//            ->setPostalCode('1137');
//        $em = $this->getDoctrine()->getManager();
//        $em->persist($property);
//        $em->flush();

//        $repository = $this->getDoctrine()->getRepository(Property::class);

        $property = $this->repository->findAllVisible();
//        $property[0]->setSold(false);
//        $this->em->flush();
        dump($property);
       return new Response($this->renderView('property/index.html.twig',[
           'current_menu' => 'properties'
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