<?php
namespace PH7\Learnphp\endpoints;



use PH7\Learnphp\exception\InvalidValidationException;
use Respect\Validation\Validator as v;
class User{
    public function __construct(
public readonly string $name,
public readonly string $email,
public readonly string $phone,

    ){


    }

    public function create(mixed $data): object{
        $minimumNameLength = 2;
        $maximumNameLength = 30;


     $schemeValidation = v::attribute('first', v::stringType()->length($minimumNameLength, $maximumNameLength))
        ->attribute('last', v::stringType()->length($minimumNameLength  , $maximumNameLength))
            ->attribute('email', v::email(),mandatory: false)
            ->attribute('phone', v::phone(),mandatory: false);
    if($schemeValidation->validate($data)){
        return $data;
    }
    else{
        throw new InvalidValidationException("invalid data");
    }

    }

    public function retrieveAll(): array{
return [];
}

public function retrieve(): self{
return $this;
}

public function update(): self{
    return $this;
}

public function remove(self $user): self{
    return $this;
}



}