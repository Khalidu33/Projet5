<?php
namespace App\Controller\Admin;

use App\Entity\Accommodation;
use App\Form\AccommodationType;
use App\Repository\AccommodationRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminAccommodationController extends AbstractController
{
    /**
     * @var AccommodationRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(AccommodationRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin/accommodation", name="admin.accommodation.index")
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $accommodations = $paginator->paginate(
            $this->repository->findAllQuery(),
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('admin/accommodation/index.html.twig', [
            'accommodations' => $accommodations,
            'c_menu' => 'accommodations'
        ]);
    }

    /**
     * @Route("/admin/accommodation/create", name="admin.accommodation.new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */

    public function new(Request $request)
    {
        $accommodation = new Accommodation();

        $form = $this->createForm(AccommodationType::class, $accommodation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->em->persist($accommodation);
            $this->em->flush();
            $this->addFlash('success', 'Hébergement crée avec succès');
            return $this->redirectToRoute('admin.accommodation.index');
        }

        return $this->render('admin/accommodation/new.html.twig', [
            'accommodation' => $accommodation,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/accommodation/{id}", name="admin.accommodation.edit",  methods="GET|POST")
     * @param Accommodation $accommodation
     * @param Request $request
     * @return Response
     */

    public function edit(Accommodation $accommodation, Request $request)
    {
        $form = $this->createForm(AccommodationType::class, $accommodation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->em->flush();
            $this->addFlash('success', 'Hébergement modifié avec succès');
            return $this->redirectToRoute('admin.accommodation.index');
        }

        return $this->render('admin/accommodation/edit.html.twig', [
            'accommodation' => $accommodation,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/accommodation/{id}", name="admin.accommodation.delete", methods="DELETE")
     * @param Accommodation $accommodation
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */

    public function delete(Accommodation $accommodation, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' .$accommodation->getId(), $request->get('_token')))
        {
            $this->em->remove($accommodation);
            $this->em->flush();
            $this->addFlash('success', 'Hébergement supprimé avec succès');
        }
        return $this->redirectToRoute('admin.accommodation.index');
    }

}