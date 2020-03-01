<?php
namespace App\Controller;
use App\Repository\AccommodationRepository;
use App\Repository\AttractionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HomeController extends AbstractController
{
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/", name="home")
     * @param AccommodationRepository $repository
     * @param AttractionRepository $arepository
     * @return Response
     */

    public function index(AccommodationRepository $repository, AttractionRepository $arepository): Response
    {
        $accommodations = $repository->findLatest();
        $attractions = $arepository->findLatest();
        return $this->render('home/home.html.twig', [
            'accommodations' => $accommodations,
            'attractions' => $attractions
        ]);
    }

    /**
     * @Route("/a_propos", name="about")
     */

    public function about(): Response
    {
        return $this->render('about/about.html.twig', [
 
        ]);
    }

    /**
     * @Route("/mentions_legales", name="legalNotice")
     */

    public function legalNotice(): Response
    {
        return $this->render('about/legalNotice.html.twig', [
 
        ]);
    }

    /**
     * @Route("/conditions_generales_dutilisation", name="termsOfService")
     */

    public function termsOfService(): Response
    {
        return $this->render('about/termsOfService.html.twig', [
 
        ]);
    }

    /**
     * @Route("/conditions_generales_de_vente", name="TermsofSales")
     */

    public function TermsofSales(): Response
    {
        return $this->render('about/TermsofSales.html.twig', [
 
        ]);
    }
}