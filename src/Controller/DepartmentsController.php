<?php

namespace App\Controller;

use App\Repository\CollegeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DepartmentsController extends AbstractController
{
    /**
     * @Route("/departments", name="departments")
     */
    public function index(CollegeRepository $repo)
    {
        $colleges = $repo->fetchAllHydrated();
        return $this->render('departments/index.html.twig', ['colleges' => $colleges]);
    }
}