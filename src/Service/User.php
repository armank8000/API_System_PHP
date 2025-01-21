<?php
namespace PH7\Learnphp\Service;


use PH7\JustHttp\StatusCode;
use PH7\Learnphp\DAL\UserDal;
use PH7\Learnphp\Entity\User as UserEntity;
use PH7\Learnphp\routes\Http;
use PH7\Learnphp\validation\exception\EmailExistException;
use PH7\Learnphp\validation\exception\InvalidValidationException;
use PH7\Learnphp\validation\exception\NotFoundException;
use PH7\Learnphp\validation\UserValidation;
use PH7\PhpHttpResponseHeader\Http as HttpResponse;
use Ramsey\Uuid\Uuid;
use Respect\Validation\Validator as v;

class User{

//    public readonly ?string $userId;
    const  DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    public function create(mixed $data): object|array
    {

        $userValidation = new UserValidation($data);
        $userId = Uuid::uuid4();
        if ($userValidation->isCreationSchemaValid()) {
            $userEntity = new UserEntity();
            $userEntity
                ->setUserUuid($userId)
                ->setFirstName($data->first)
                ->setLastName($data->last)
                ->setPassword(password_hash($data->password, PASSWORD_ARGON2I))
                ->setEmail($data->email)
                ->setPhone($data->phone)
                ->setCreatedDate(date(self::DATE_TIME_FORMAT));


            if (UserDal::doesEmailExist($userEntity->getEmail())) {
                throw new EmailExistException('Email already exist');
            }

            if (UserDal::create($userEntity) === false) {
                HttpResponse::setHeadersByCode(StatusCode::INTERNAL_SERVER_ERROR);
                $data = [];
            }

          // Send a 201 when the user has been successfully added to DB
        HttpResponse::setHeadersByCode(StatusCode::CREATED);


        $data->userUuid = $userId;

        return $data;
        }
            HttpResponse::setHeadersByCode(StatusCode::BAD_REQUEST);
        throw new InvalidValidationException("invalid data");

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
    HttpResponse::setHeadersByCode(StatusCode::NOT_FOUND);

    throw new InvalidValidationException("invalid UUID");
}

public function update(mixed $postBody): object|array{



    $userValidation = new UserValidation($postBody);
    if($userValidation->isUpdateSchemaValid()){
        $userUuid = $postBody->user_uuid;
        $userEntity = new UserEntity();
        if(!empty($postBody->first)){
            $userEntity->setFirstName($postBody->first);
        }
        if(!empty($postBody->last)){
            $userEntity->setLastName($postBody->last);
        }
        if(!empty($postBody->phone)){
            $userEntity->setPhone($postBody->phone);
        }

       if( UserDal::update($userUuid, $userEntity)===false) {

           HttpResponse::setHeadersByCode(StatusCode::BAD_REQUEST);

           return [];
       }
       return  $postBody;
}
    throw new InvalidValidationException("invalid data");

    }

public function remove(mixed $data): bool{




    $userSchema = new UserValidation($data);
    if($userSchema->isRemoveSchemaValid()){
       return UserDal::remove($data->user_uuid);

    }
    else{
        HttpResponse::setHeadersByCode(StatusCode::NOT_FOUND);

        throw new InvalidValidationException("invalid UUID");
    }
}



}