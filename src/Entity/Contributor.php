<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Contributor extends User
{
    public const ROLE = "contributor";

    public function getRoles(): array
    {
        return ['ROLE_CONTRIBUTOR'];
    }
}