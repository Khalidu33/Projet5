<?php
namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{

    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(UserRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin/utilisateur", name="admin.user.index")
     * @return Response
     */

    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $users = $paginator->paginate(
            $this->repository->findAllQuery(),
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
            'c_menu' => 'user',
        ]);
    }

    /**
     * @Route("/admin/utilisateur/edit/{id}", name="admin.user.edit", methods="GET|POST")
     * @param Request $request
     * @return Response
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */

    public function edit(Request $request, $id)
    {
        $roles = $request->get('roles');
        $role = $this->repository->find($id);
        $role->setRoles($roles);
        $this->em->flush();
        $this->addFlash('success', 'Utilisateur modifié avec succès');

        return $this->redirectToRoute('admin.user.index');
    }
}
