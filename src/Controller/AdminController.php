<?php

namespace App\Controller;

use App\Service\SpreadsheetLoader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    
    /**
     * @Route("/admin/load-departments", name="load_departments")
     */
    public function loadDepartments(Request $request, SpreadsheetLoader $loader)
    {
        $file = $request->files->get('file');
        $loader->loadDepartments($file);
    }
}
