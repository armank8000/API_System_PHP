<?php

namespace PH7\Learnphp\validation;

use Respect\Validation\Validator as v;

class UserValidation
{

private const minimumNameLength = 2;
private const maximumNameLength = 30;
public function __construct(private readonly mixed $data){

}



public function isCreationSchemaValid(): bool{



    $schemeValidation = v::attribute('first', v::stringType()->length(self::minimumNameLength, self::maximumNameLength))
        ->attribute('last', v::stringType()->length(self::minimumNameLength  , self::maximumNameLength))
        ->attribute('email', v::email(),mandatory: false)
        ->attribute('phone', v::phone(),mandatory: false);
    return $schemeValidation->validate($this->data);

}

    public function isUpdateSchemaValid(): bool{

        return $this->isCreationSchemaValid();

    }

    public function isRemoveSchemaValid(): bool{
      return  v::attribute('user_uuid',v::uuid(version: 4))->validate($this->data);
    }






}