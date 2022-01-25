<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 */
class Telephone extends Constraint
{
 public $message = "Ce numero de telephone n'est pas valide";
}