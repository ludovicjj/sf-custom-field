<?php

namespace App\DataTransfertObject;

use Symfony\Component\Validator\Constraints as Assert;

class UserDto
{
    #[Assert\NotBlank]
    public ?string $firstname;

    #[Assert\NotBlank]
    public ?string $lastname;

    public ?string $password;

    public function __construct(
        ?string $firstname,
        ?string $lastname,
        ?string $password
    )
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->password = $password;
    }
}