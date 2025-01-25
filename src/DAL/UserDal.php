<?php

namespace PH7\Learnphp\DAL;
use PH7\JustHttp\StatusCode;
use PH7\Learnphp\Entity\User as UserEntity;
use PH7\PhpHttpResponseHeader\Http;
use RedBeanPHP\R;
use RedBeanPHP\RedException\SQL;

final class UserDal
{

   public const TABLE_NAME = 'users';


    public static function create(UserEntity $userEntity) : int|string|false
    {
        $userBean = R::dispense(self::TABLE_NAME);
        $userBean->user_uuid = $userEntity->getUserUuid();
        $userBean->first_name = $userEntity->getFirstName();
        $userBean->last_name = $userEntity->getLastName();
        $userBean->email = $userEntity->getEmail();
        $userBean->phone = $userEntity->getPhone();
        $userBean->password = $userEntity->getPassword();
        $userBean->created_date = $userEntity->getCreatedDate();


        try {
            return R::store($userBean);

        } catch (SQL $exception) {
            Http::setHeadersByCode(StatusCode::BAD_REQUEST);
        } finally {
            R::close();
        }
        return false;
    }


    public static function update(string $userId, UserEntity $userEntity) : int|string|false {
       $userBean = R::findOne(self::TABLE_NAME, 'user_uuid = :userUuid', ['userUuid' => $userId]);
       if($userBean){
           $firstname= $userEntity->getFirstName();
           $phone = $userEntity->getPhone();
           $lastname = $userEntity->getLastName();
           if($firstname){
               $userBean->firstname = $firstname;
        }
           if($lastname){
               $userBean->lastname = $lastname;
           }
           if($phone){
               $userBean->phone  = $phone;
           }

           try{
               return R::store($userBean);

           }catch (SQL $exception){
               Http::setHeadersByCode(StatusCode::BAD_REQUEST);
           }finally{
               R::close();
           }
return false;

       }
  return false;

   }

public static function getByEmail(string $email): UserEntity{
        $binding = ['email' => $email];
        $user = R::findOne(self::TABLE_NAME, 'email = :email', $binding);
   return (new UserEntity())->unserialize($user?->export());
    }

   public static function get(string $userUuid): UserEntity{
      $data= R::findOne(self::TABLE_NAME, 'user_uuid = :userUuid', ['userUuid' => $userUuid]);
       return (new UserEntity())->unserialize($data?->export());
   }
   public static function getAll(): array{
       $data =  R::findAll(self::TABLE_NAME);
       if($data && count($data)){
           return array_map(function (object $data) {
               $userEntity = (new UserEntity())->unserialize($data->export());


                   return [
                       "id" => $userEntity->getUserUuid(),
                       "first" => $userEntity->getFirstName(),
                       "last" => $userEntity->getLastName(),
                       "email" => $userEntity->getEmail(),
                       "phone" => $userEntity->getPhone(),
                       "created_date" => $userEntity->getCreatedDate(),
                   ];


           }, $data);
       }
       return [];
   }

   public static function remove(string $userUuid): bool{
       $userBean = R::findOne(self::TABLE_NAME, 'user_uuid = :userUuid', ['userUuid' => $userUuid]);
       if($userBean){
           return (bool)R::trash($userBean);
       }
       return false;
   }


    public static function doesEmailExist(string $email): bool{
       return R::findOne(self::TABLE_NAME, 'email = :email', ['email' => $email]) !== null;
    }



}