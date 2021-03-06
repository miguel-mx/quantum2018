<?php
// src/AppBundle/Form/CommentType
namespace AppBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('file','Symfony\Component\Form\Extension\Core\Type\TextType', array('required' => false));
        $builder->add('granted', 'Symfony\Component\Form\Extension\Core\Type\CheckboxType', array(
                'required' => false,
            ));
        $builder->add('confirmed', 'Symfony\Component\Form\Extension\Core\Type\CheckboxType', array(
            'required' => false,
        ));
        $builder->add('comments','Symfony\Component\Form\Extension\Core\Type\TextareaType', array('required' => false));
    }

    public function getName()
    {
        return 'app_registration_comment';
    }
}