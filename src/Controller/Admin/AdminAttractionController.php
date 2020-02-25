<?php
namespace App\Controller\Admin;

use App\Entity\Attraction;
use App\Form\AttractionType;
use App\Repository\AttractionRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminAttractionController extends AbstractController
{

    /**
     * @var AttractionRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(AttractionRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin/attraction", name="admin.attraction.index")
     * @return Response
     */

    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $attractions = $paginator->paginate(
            $this->repository->findAllQuery(),
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('admin/attraction/index.html.twig', [
            'attractions' => $attractions,
            'c_menu' => 'attractions'
        ]);
    }

    /**
     * @Route("/admin/attraction/create", name="admin.attraction.new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */

    public function new(Request $request)
    {
        $attraction = new Attraction();

        $form = $this->createForm(AttractionType::class, $attraction);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $this->em->persist($attraction);
            $this->em->flush();
            $this->addFlash('success', 'Attraction crée avec succès');
            return $this->redirectToRoute('admin.attraction.index');
        }

        return $this->render('admin/attraction/new.html.twig', [
            'attraction' => $attraction,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/attraction/{id}", name="admin.attraction.edit", methods="GET|POST")
     * @param Attraction $attraction
     * @param Request $request
     * @return Response
     */

    public function edit(Attraction $attraction, Request $request)
    {
        $form = $this->createForm(AttractionType::class, $attraction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->em->flush();
            $this->addFlash('success', 'Attraction modifié avec succès');
            return $this->redirectToRoute('admin.attraction.index');
        }

        return $this->render('admin/attraction/edit.html.twig', [
            'attraction' => $attraction,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/attraction/{id}", name="admin.attraction.delete", methods="DELETE")
     * @param Attraction $attraction
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */

    public function delete(Request $request, Attraction $attraction)
    {
        if ($this->isCsrfTokenValid('delete' .$attraction->getId(), $request->get('_token')))
        {
            $this->em->remove($attraction);
            $this->em->flush();
            $this->addFlash('success', 'Attraction supprimé avec succès');
        }
        return $this->redirectToRoute('admin.attraction.index');
    }
}