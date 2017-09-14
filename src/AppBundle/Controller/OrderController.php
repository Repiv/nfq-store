<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use AppBundle\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Order controller.
 *
 * @Route("admin")
 */
class OrderController extends Controller {

    /**
     * Lists all order entities.
     *
     * @Route("/", name="order_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $perPage = 10;
        
        $page = $request->get('page', 1);
        $sort = $request->get('sort', 'created');
        $sortOrder = $request->get('ord', 'desc');
        
        if(!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }
        
        $form = $this->createForm('AppBundle\Form\SearchType', null, [
            'action' => $this->generateUrl('order_index'),
            'method' => 'GET'
        ]);
        
        $form->handleRequest($request);
        
        $term = false;
        
        if ($form->isSubmitted() && $form->isValid()) {
            $term = $form->get('term')->getData();
        }
        
        $ordersCount = $this->getDoctrine()->getManager()->getRepository(Order::class)->getOrdersPaginated(false, false, false, false, $term, $count = true);
        $orders = $this->getDoctrine()->getManager()->getRepository(Order::class)->getOrdersPaginated($page, $perPage, $sort, $sortOrder, $term);
        
        return $this->render('order/index.html.twig', [
            'orders' => $orders,
            'form' => $form->createView(),
            'page' => $page,
            'sort' => $sort,
            'order' => $sortOrder,
            'pages' => ceil($ordersCount / $perPage),
        ]);
    }

    /**
     * Finds and displays a order entity.
     *
     * @Route("/{id}", name="order_show")
     * @Method("GET")
     */
    public function showAction(Order $order)
    {
        return $this->render('order/show.html.twig', ['order' => $order]);
    }

}
