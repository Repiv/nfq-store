<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\Order;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;

class DefaultController extends Controller {

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $products = $this->getDoctrine()->getManager()->getRepository(Product::class)->findAll();

        $form = $this->createForm('AppBundle\Form\OrderType', null, [
            'action' => $this->generateUrl('order_new')
        ]);

        return $this->render('default/index.html.twig', [
                    'products' => $products,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a new order entity.
     *
     * @Route("/order-new", name="order_new")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
        $translator = $this->get('translator');
        $order = new Order();
        $form = $this->createForm('AppBundle\Form\OrderType', $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $total = 0;
            
            foreach($form->get('products')->getData() as $product) {
                $total += $product->getPrice();
            }
            
            $order->setTotal($total);
            
            $em->persist($order);
            $em->flush();

            return new JsonResponse([$translator->trans('order.created')], 200);
        }
        
        $errors = $this->getErrorMessages($form);

        return new JsonResponse($errors, 500);
    }

    protected function getErrorMessages(Form $form)
    {
        $errors = [];

        foreach ($form->getErrors() as $key => $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }

}
