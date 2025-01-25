<?php
namespace PH7\Learnphp\Service;


use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PH7\JustHttp\StatusCode;
use PH7\Learnphp\DAL\UserDal;
use PH7\Learnphp\Entity\User as UserEntity;
use PH7\Learnphp\validation\exception\EmailExistException;
use PH7\Learnphp\validation\exception\InvalidCredentialException;
use PH7\Learnphp\validation\exception\InvalidValidationException;
use PH7\Learnphp\validation\UserValidation;
use PH7\PhpHttpResponseHeader\Http as HttpResponse;
use Ramsey\Uuid\Uuid;
use Respect\Validation\Validator as v;

class User{

//    public readonly ?string $userId;
    const  DATE_TIME_FORMAT = 'Y-m-d H:i:s';


    public function login(mixed $data): ?array
    {
        $userValidation = new UserValidation($data);
        if ($userValidation->isLoginSchemaValid()) {
            if (userDal::doesEmailExist($data->email)) {
                $user = UserDal::getByEmail($data->email);
                if ($user->getEmail() && password_verify($data->password, $user->getPassword())) {
                    $userName = "{$user->getFirstName()} {$user->getLastName()}";
                    $currentTime = time();
                    $jwtToken = JWT::encode(
                        [
                            'iss' => $_ENV['APP_URL'],
                            'lat' => $currentTime,
                            'exp' => $currentTime + $_ENV['JWT_TIME_EXPIRATION'],
                            'data' => [
                                'email' => $data->email,
                                'name' => $userName,
                            ]
                        ],
                        $_ENV['JWT_KEY'],
                        $_ENV['JWT_ALGO_ENCRYPTION']


                    );


                    return [
                        'token' => $jwtToken,
                        'message' => sprintf('%s successfully logged in', $userName)
                    ];
                }
            }
                throw new InvalidCredentialException('Credentials invalid');
            }
            throw new InvalidValidationException("invalid email");
        }

public function validateToken(mixed $token): bool{
    try{
        $decoded = JWT::decode($token->h, new Key($_ENV['JWT_KEY'], $_ENV['JWT_ALGO_ENCRYPTION'],));
        print_r($decoded->exp);
        return true;
    }catch (ExpiredException $e){
        throw new ExpiredException('Expired token');

    }
}

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

    public function retrieveAll(): ?array{
       return UserDal::getAll();


}

public function retrieve(string $userId): ?array{


    if(v::uuid(version: 4)->validate($userId)){
      if ($data = UserDal::get($userId)){
         if($data->getUserUuid() ){
             return [
                 "id" => $data->getUserUuid(),
                 "first" => $data->getFirstName(),
                 "last" => $data->getLastName(),
                 "email" => $data->getEmail(),
                 "phone" => $data->getPhone(),
                 "created_date" => $data->getCreatedDate(),
             ];
         }
      }


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