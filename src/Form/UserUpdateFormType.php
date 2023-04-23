<?php

namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserUpdateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'attr' => [
                    'class' => 'form-input',
                    'data-validate' => 'require',
                    'placeholder' => 'send@test.test',
                ]
            ])
            ->add('phone', TextType::class, [
                'label' => 'Телефон',
                'attr' => [
                    'class' => 'form-input',
                    'data-validate' => 'require',
                    'placeholder' => 'send@test.test',
                ]
            ])
            ->add('fio', TextType::class, [
                'label' => 'ФИО',
                'attr' => [
                    'class' => 'form-input',
                    'data-validate' => 'require',
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Пароль',
                'required' => false,
                'attr' => [
                    'class' => 'form-input',
                    'placeholder' => 'Выберите пароль',
                ]
            ])
            ->add('plainPasswordReply', PasswordType::class, [
                'label' => 'Подтверждение пароля',
                'required' => false,
                'attr' => [
                    'class' => 'form-input',
                    'placeholder' => 'Введите пароль повторно',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
