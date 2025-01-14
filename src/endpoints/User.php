<?php
namespace PH7\Learnphp\endpoints;


use PH7\JustHttp\StatusCode;
use PH7\Learnphp\validation\exception\InvalidValidationException;
use PH7\Learnphp\validation\UserValidation;
use PH7\PhpHttpResponseHeader\Http;
use Respect\Validation\Validator as v;

class User{

    public readonly ?string $userId;
    public function __construct(
public readonly string $name,
public readonly string $email,
public readonly string $phone,
    ){


    }

    public function create(mixed $data): object{
         print_r($data);
        $userValidation = new UserValidation($data);
    if($userValidation->isCreationSchemaValid()){
        return $data;
    }
    else{
        Http::setHeadersByCode(StatusCode::BAD_REQUEST);
        throw new InvalidValidationException("invalid data");
    }

    }

    public function retrieveAll(): array{
return [];
}

public function retrieve(string $userId): self{


    if(v::uuid(version: 4)->validate($userId)){
        $this->userId = $userId;
        return $this;
    }
    Http::setHeadersByCode(StatusCode::NOT_FOUND);

    throw new InvalidValidationException("invalid UUID");
}

public function update(mixed $postBody): object{
    $userValidation = new UserValidation($postBody);
    if($userValidation->isUpdateSchemaValid()){
        return $postBody;
}
    Http::setHeadersByCode(StatusCode::BAD_REQUEST);
    throw new InvalidValidationException("invalid data");

    }

public function remove(string $userId): bool{
    if(v::uuid(version: 4)->validate($userId)){
        $this->userId = $userId;

    }
    else{
        Http::setHeadersByCode(StatusCode::NOT_FOUND);

        throw new InvalidValidationException("invalid UUID");
    }
        return true;
}



}