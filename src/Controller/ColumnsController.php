<?php

namespace App\Controller;

use App\Entity\Columns;
use App\Form\ColumnsType;
use App\Repository\ColumnsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/columns")
 */
class ColumnsController extends AbstractController
{
    /**
     * @Route("/", name="columns_index", methods={"GET"})
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        // Retrieve the entity manager of Doctrine
        $em = $this->getDoctrine()->getManager();
        // Get some repository of data, in our case we have an Columns entity
        $columnsRepository = $em->getRepository(Columns::class);
        // Find all the data on the Columns table, filter your query as you need
        $allColumnsQuery = $columnsRepository->createQueryBuilder('c')
            ->getQuery();

        // Paginate the results of the query
        $paginator = $paginator->paginate(
        // Doctrine Query, not results
            $allColumnsQuery,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            15
        );
        return $this->render('columns/index.html.twig', [
            'pagination' => $paginator,
        ]);
    }

    /**
     * @Route("/new", name="columns_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $column = new Columns();
        $form = $this->createForm(ColumnsType::class, $column);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($column);
            $entityManager->flush();

            return $this->redirectToRoute('columns_index');
        }

        return $this->render('columns/new.html.twig', [
            'column' => $column,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="columns_show", methods={"GET"})
     */
    public function show(Columns $column): Response
    {
        return $this->render('columns/show.html.twig', [
            'column' => $column,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="columns_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Columns $column): Response
    {
        $form = $this->createForm(ColumnsType::class, $column);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('columns_index');
        }

        return $this->render('columns/edit.html.twig', [
            'column' => $column,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="columns_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Columns $column): Response
    {
        if ($this->isCsrfTokenValid('delete'.$column->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($column);
            $entityManager->flush();
        }

        return $this->redirectToRoute('columns_index');
    }
}
