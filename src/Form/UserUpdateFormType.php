<?php

namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

class UserUpdateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var User|null $user */
        $user = $options['data'] ?? null;

        $imageConstrains = [
            new Image([
                'maxSize' => '2M',
            ]),
        ];

        if (!$user || !$user->getAvatar()) {
            $imageConstrains[] = new NotNull([
                'message' => 'Не выбрано изображение',
            ]);
        }

        $builder
            ->add('image', FileType::class, [
                    'mapped' => false,
                    'required' => false,
                    'constraints' => $imageConstrains,
                    'label' => 'Аватар',
                    'label_attr' => ['class' => 'form-label'],
                    'attr' => [
                        'class' => 'Profile-file form-input',
                        'data-validate' => "onlyImgAvatar",
                    ]
                ]
            )
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'label_attr' => ['class' => 'form-label'],
                'attr' => [
                    'class' => 'form-input',
                    'data-validate' => 'require',
                    'placeholder' => 'send@test.test',
                ]
            ])
            ->add('phone', TextType::class, [
                'label' => 'Телефон',
                'label_attr' => ['class' => 'form-label'],
                'attr' => [
                    'class' => 'form-input',
                    'data-validate' => 'require',
                    'placeholder' => 'send@test.test',
                ]
            ])
            ->add('fio', TextType::class, [
                'label' => 'ФИО',
                'label_attr' => ['class' => 'form-label'],
                'attr' => [
                    'class' => 'form-input',
                    'data-validate' => 'require',
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Пароль',
                'label_attr' => ['class' => 'form-label'],
                'required' => false,
                'attr' => [
                    'class' => 'form-input',
                    'placeholder' => 'Выберите пароль',
                ]
            ])
            ->add('plainPasswordReply', PasswordType::class, [
                'label' => 'Подтверждение пароля',
                'label_attr' => ['class' => 'form-label'],
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
