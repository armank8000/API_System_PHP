<?php
namespace PH7\Learnphp\Service;


use PH7\JustHttp\StatusCode;
use PH7\Learnphp\DAL\UserDal;
use PH7\Learnphp\Entity\User as UserEntity;
use PH7\Learnphp\validation\exception\InvalidValidationException;
use PH7\Learnphp\validation\UserValidation;
use PH7\PhpHttpResponseHeader\Http;
use Ramsey\Uuid\Uuid;
use RedBeanPHP\RedException\SQL;
use Respect\Validation\Validator as v;

class User{

//    public readonly ?string $userId;
    const  DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    public function create(mixed $data): object{

        $userValidation = new UserValidation($data);
    if($userValidation->isCreationSchemaValid()){
        $userId = Uuid::uuid4();
        $userEntity = new UserEntity();
        $userEntity
            ->setUserUuid($userId)
            ->setFirstName($data->first)
            ->setLastName($data->last)
            ->setEmail($data->email)
            ->setPhone($data->phone)
            ->setCreatedDate(date(self::DATE_TIME_FORMAT));
try{
    UserDal::create($userEntity);

}catch (SQL $exception){
    Http::setHeadersByCode(StatusCode::INTERNAL_SERVER_ERROR);

    $data=[];
}



        return $data;
    }
    else{
        Http::setHeadersByCode(StatusCode::BAD_REQUEST);
        throw new InvalidValidationException("invalid data");
    }

    }

    public function retrieveAll(): array{
       $users = UserDal::getAll();
       return array_map(function (object $data){
           unset($data['id']);
           return $data;
       }, $users);

}

public function retrieve(string $userId): ?array{


    if(v::uuid(version: 4)->validate($userId)){
       $data = UserDal::get($userId);
        unset($data['id']);
        return $data;
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

public function remove(object $data): bool{
        $userSchema = new UserValidation($data);
    if($userSchema->isRemoveSchemaValid()){
       return UserDal::remove($data->user_uuid);

    }
    else{
        Http::setHeadersByCode(StatusCode::NOT_FOUND);

        throw new InvalidValidationException("invalid UUID");
    }
}



}