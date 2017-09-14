<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class OrderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name', null, ['label' => 'order.form.name', 'required' => true])
                ->add('email', EmailType::class, ['label' => 'order.form.email'])
                ->add('address', null, ['label' => 'order.form.address'])
                ->add('phone', null, ['label' => 'order.form.phone'])
                ->add('comment', null, ['label' => 'order.form.comment'])
                ->add('products');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Order'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_order';
    }


}
