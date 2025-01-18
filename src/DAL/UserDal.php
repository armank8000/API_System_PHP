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


    public static function create(UserEntity $userEntity) : int|string|false {
       $userBean = R::dispense(self::TABLE_NAME);
       $userBean->user_uuid = $userEntity->getUserUuid();
        $userBean->firstname = $userEntity->getFirstName();
       $userBean->last_name = $userEntity->getLastName();
       $userBean->email = $userEntity->getEmail();
       $userBean->phone = $userEntity->getPhone();
       $userBean->created_date = $userEntity->getCreatedDate();


        try{
            return R::store($userBean);

        }catch (SQL $exception){
           Http::setHeadersByCode(StatusCode::BAD_REQUEST);
        }finally{
            R::close();
        }

       R::close();
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



   public static function get(string $userUuid): ?array{
      $data= R::findOne(self::TABLE_NAME, 'user_uuid = :userUuid', ['userUuid' => $userUuid]);
       return $data->export();
   }
   public static function getAll(): array{
       return R::findAll(self::TABLE_NAME);

   }

   public static function remove(string $userUuid): bool{
       $userBean = R::findOne(self::TABLE_NAME, 'user_uuid = :userUuid', ['userUuid' => $userUuid]);
       if($userBean){
           return (bool)R::trash($userBean);
       }
       return false;
   }




}