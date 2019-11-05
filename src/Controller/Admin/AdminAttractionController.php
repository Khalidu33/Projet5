<?php
namespace App\Controller\Admin;

use App\Entity\Attraction;
use App\Form\AttractionType;
use App\Repository\AttractionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminAttractionController extends AbstractController
{

    /**
     * @var AttractionRepository
     */
    private $repository;

    public function __construct(AttractionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/admin", name="admin.attraction.index")
     * @return Response
     */

    public function index(): Response
    {
        $attractions = $this->repository->findAll();
        return $this->render('admin/attraction/index.html.twig', compact('attractions'));
    }

    /**
     * @Route("/admin/{id}", name="admin.attraction.edit")
     * @param Attraction $attraction
     * @return Response
     */

    public function edit(Attraction $attraction)
    {
        $form = $this->createForm(AttractionType::class, $attraction);
        return $this->render('admin/attraction/edit.html.twig', [
            'attraction' => $attraction,
            'form' => $form->createView()
        ]);
    }
}