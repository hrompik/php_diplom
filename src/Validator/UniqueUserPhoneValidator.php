<?php

namespace App\Validator;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueUserPhoneValidator extends ConstraintValidator
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint UniqueUserPhone */

        if (null === $value || '' === $value) {
            return;
        }
        $user = new User();
        $user->setPhone($value);

        if (! $this->userRepository->findOneBy(['phone' => $user->getPhone()])) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
