<?php

namespace App\Form;

use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('receiver', null,[
                'label' => 'Destinataire :',
                'multiple' => false,
                'placeholder' => 'Choisir un destinataire',   
            ])
            ->add('title', null, [
                'label' => 'Intitulé :' 
            ])
            ->add('body', CKEditorType::class,[
                'label' => 'Message :',
              
            ])     
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
