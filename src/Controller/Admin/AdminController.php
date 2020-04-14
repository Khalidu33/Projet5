<?php
namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin.index")
     * @return Response
     */

    public function index(PaginatorInterface $paginator, Request $request, UserRepository $repository): Response
    {
        $admins = $paginator->paginate(
            $repository->findAllQuery(),
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('admin/index.html.twig', [
            'admins' => $admins,
            'c_menu' => 'admins'
        ]);
    }
}