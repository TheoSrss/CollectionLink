<?php

namespace App\Validator\Constraints;

use App\DTO\PasswordForgottenNewPasswordInput;
use App\Repository\UserRepository;
use App\Validator\Constraints\IsValidPasswordForgottenCode;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class IsValidPasswordForgottenCodeValidator extends ConstraintValidator
{
    public function __construct(private UserRepository $userRepository) {}

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof IsValidPasswordForgottenCode) {
            throw new UnexpectedTypeException($constraint, IsValidPasswordForgottenCode::class);
        }

        if (!$value instanceof PasswordForgottenNewPasswordInput) {
            throw new UnexpectedTypeException($value, PasswordForgottenNewPasswordInput::class);
        }
        $user = $this->userRepository->findOneBy(['email' => $value->email]);

        if (null === $user) {
            $this->context->buildViolation($constraint->messageUserNotFound)
                ->atPath('email')
                ->setCode(IsValidPasswordForgottenCode::USER_NOT_FOUND_ERROR)
                ->addViolation();
            return;
        }

        if ($value->code != $user->getPasswordForgottenCode()) {
            $this->context->buildViolation($constraint->messageCodeInvalid)
                ->atPath('code')
                ->setCode(IsValidPasswordForgottenCode::CODE_INVALID_ERROR)
                ->addViolation();
        }
    }
}
