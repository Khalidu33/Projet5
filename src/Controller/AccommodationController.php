<?php
namespace App\Controller;

use App\Entity\Accommodation;
use App\Repository\AccommodationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccommodationController extends AbstractController
{

    /**
     * @var AccommodationRepository
     */
    private $repository;

    public function __construct(AccommodationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/hébergements", name="accommodation.index")
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $accommodations = $paginator->paginate(
            $this->repository->findAllQuery(),
            $request->query->getInt('page', 1),
            6
        );
        return $this->render('accommodation/index.html.twig', [
            'accommodations' => $accommodations,
            'c_menu' => 'accommodations',
        ]);
    }

    /**
     * @Route("/accommodation/{slug}-{id}", name="accommodation.show", requirements={"slug": "[a-z0-9\-]*"})
     * @param Accommodation $accommodation
     * @param string $slug
     * @return Response
     */

    public function show(Accommodation $accommodation, string $slug): Response
    {
        if ($accommodation->getSlug() !== $slug) {
            return $this->redirectToRoute('attraction.show', [
                'id' => $accommodation->getId(),
                'slug' => $accommodation->getSlug(),
            ], 301);
        }

        return $this->render('accommodation/show.html.twig', [
            'accommodation' => $accommodation,
            'c_menu' => 'accommodations',
        ]);
    }
}
