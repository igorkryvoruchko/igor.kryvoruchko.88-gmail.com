<?php

namespace App\Controller;

use App\Entity\AdditionalFields;
use App\Entity\Columns;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ColumnsRepository;
use App\Repository\ProductRepository;
use App\Services\SaveAdditionalFields;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product_index", methods={"GET"})
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository(Product::class);// Get some repository of data, in our case we have an Product entity
        // Find all the data on the Product table, filter your query as you need
        $allProductQuery = $productRepository->createQueryBuilder('p');
        if($request->query->get('search_name')){
           $allProductQuery->where('p.name LIKE :name')->setParameter('name', '%'.(string) $request->query->get('search_name').'%');
        }
        $allProductQuery->getQuery();
        $paginator = $paginator->paginate(// Paginate the results of the query
            $allProductQuery,
            $request->query->getInt('page', 1),// Define the page parameter
            20 // Items per page
        );
        return $this->render('product/index.html.twig', [
            'pagination' => $paginator,
        ]);
    }

    /**
     * @Route("/new", name="product_new", methods={"GET","POST"})
     */
    public function new(Request $request, ColumnsRepository $columns): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($request->request->get('custom') as $key => $value) {
                $additional = new AdditionalFields();
                $additional->setColumnId($key);
                $additional->setValue($value);
                $product->addAdditional($additional);
                $entityManager->persist($additional);
            }
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'columns' => $columns->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_show", methods={"GET"})
     */
    public function show(Product $product, ColumnsRepository $columns): Response
    {
        $additional = $product->getAdditional()->getValues();
        return $this->render('product/show.html.twig', [
            'product' => $product,
            'additional' => $additional,
            'columns' => $columns->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product, ColumnsRepository $columns): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();


            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'columns' => $columns->findAll(),
            'additional' => $product->getAdditional()->getValues(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index');
    }
}
