<?php

namespace App\Form;

use App\Form\Model\UserRegistrationFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-input']
            ])
            ->add('phone', TextType::class, [
                'label' => 'Телефон',
                'attr' => ['class' => 'form-input']
            ])
            ->add('plainPassword', PasswordType::class, [
                'required' => false,
                'attr' => ['class' => 'form-input']
            ])
            ->add('plainPasswordReply', PasswordType::class, [
                'required' => false,
                'attr' => ['class' => 'form-input']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserRegistrationFormModel::class,
        ]);
    }
}
