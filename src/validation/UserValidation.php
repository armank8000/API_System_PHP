<?php

namespace PH7\Learnphp\validation;

use Respect\Validation\Validator as v;

class UserValidation
{

private const minimumNameLength = 2;

private const maximumNameLength = 30;


    private const maximumPasswordLength = 20;
    private const minimumPasswordLength = 8;

public function __construct(private readonly mixed $data){

}



public function isCreationSchemaValid(): bool{



    $schemeValidation = v::attribute('first', v::stringType()->length(self::minimumNameLength, self::maximumNameLength))
        ->attribute('last', v::stringType()->length(self::minimumNameLength  , self::maximumNameLength))
        ->attribute('email', v::email())
        ->attribute('password', v::stringType()->length(self::minimumPasswordLength, self::maximumPasswordLength))
        ->attribute('phone', v::phone(),mandatory: false);
    return $schemeValidation->validate($this->data);

}

    public function isUpdateSchemaValid(): bool{
    $schemaValidation =
     v::attribute('user_uuid',v::uuid(version:4))
     ->attribute('first', v::stringType()->length(self::minimumNameLength, self::maximumNameLength),false)
          ->attribute('last', v::stringType()->length(self::minimumNameLength  , self::maximumNameLength),false)
          ->attribute('phone', v::phone(),mandatory: false);
return $schemaValidation->validate($this->data);
    }

    public function isRemoveSchemaValid(): bool{
      return  v::attribute('user_uuid',v::uuid(version: 4))->validate($this->data);
    }

    public function isLoginSchemaValid(): bool
    {

        $schemaValidation = v::attribute('email', v::stringType())
            ->attribute('password', v::stringType()->length(self::minimumPasswordLength, self::maximumPasswordLength));
        return $schemaValidation->validate($this->data);

    }

}