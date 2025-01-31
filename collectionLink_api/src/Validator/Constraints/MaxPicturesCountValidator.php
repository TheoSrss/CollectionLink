<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Doctrine\Common\Collections\Collection;

class MaxPicturesCountValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof MaxPicturesCount) {
            throw new UnexpectedValueException($constraint, MaxPicturesCount::class);
        }

        if (!$value instanceof Collection) {
            return;
        }

        if ($value->count() > $constraint->limit) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ limit }}', (string) $constraint->limit)
                ->setCode('COLLECTABLE_MAX_PICTURES')
                ->addViolation();
        }
    }
}
