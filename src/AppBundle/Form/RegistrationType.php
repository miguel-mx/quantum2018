<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('surname')
            ->add('email')
            ->add('institution')
            ->add('status','Symfony\Component\Form\Extension\Core\Type\ChoiceType', array(
                'choices'  => array(
                    'Student' => 'Student',
                    'Postdoc' => 'Postdoc',
                    'Faculty' => 'Faculty',
                ),
                'choices_as_values' => true,
            ))
            ->add('arrivalDate', 'Symfony\Component\Form\Extension\Core\Type\DateType', array(
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,))
            ->add('departureDate', 'Symfony\Component\Form\Extension\Core\Type\DateType', array(
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,))
            ->add('title')
            ->add('abstract')
//            ->add('fellowship', 'Symfony\Component\Form\Extension\Core\Type\CheckboxType', array(
//                'required' => false,
//            ))
            ->add('toggleTalk', 'Symfony\Component\Form\Extension\Core\Type\CheckboxType', array(
            'mapped' => false,
            'required' => false,
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Registration'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_registration';
    }


}
