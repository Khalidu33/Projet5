<?php
namespace App\Controller;

use App\Entity\Attraction;
use App\Repository\AttractionRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AttractionController extends AbstractController
{

    /**
     * @var AttractionRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(AttractionRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/attractions", name="attraction.index")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('attraction/index.html.twig', [
            'c_menu' => 'attractions'
        ]);
    }

    /**
     * @Route("/attractions/{slug}-{id}", name="attraction.show", requirements={"slug": "[a-z0-9\-]*"})
     * @param Attraction $attraction
     * @param string $slug
     * @return Response
     */

    public function show(Attraction $attraction, string $slug): Response
    {
        if($attraction->getSlug() !== $slug)
        {
            return $this->redirectToRoute('attraction.show', [
                'id' => $attraction->getId(),
                'slug' => $attraction->getSlug()
            ],301);
        }

        return $this->render('attraction/show.html.twig', [
            'attraction' => $attraction,
            'c_menu' => 'attractions'
        ]);
    }
}