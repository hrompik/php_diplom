<?php

namespace App\Form\Model;

use App\Validator\UniqueUserEmail;
use App\Validator\UniqueUserPhone;
use App\Validator\UserPhone;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UserRegistrationFormModel
{
    #[Assert\NotBlank]
    #[Assert\Email(
        message: 'Не корректная почта',
    )]
    #[UniqueUserEmail]
    public $email;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 18,
        max: 18,
        minMessage: 'Формат номера телефона +7 (000) 000-00-00',
        maxMessage: 'Формат номера телефона +7 (000) 000-00-00',
    )]
    #[UserPhone]
    #[UniqueUserPhone]
    public $phone;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 6,
        max: 50,
        minMessage: 'Минимальная длина пароля 6',
        maxMessage: 'Максимальная длина пароля 50',
    )]
    public $plainPassword;

    public $plainPasswordReply;

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if ($this->plainPasswordReply !== $this->plainPassword) {
            $context->buildViolation('Пароли должны совпадать!')
                ->atPath('plainPassword')
                ->addViolation();
        }
    }
}
