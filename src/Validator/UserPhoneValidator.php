<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UserPhoneValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint UserPhone */

        if (null === $value || '' === $value) {
            return;
        }

        if (preg_match('/^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/', $value)) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
