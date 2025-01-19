<?php
namespace PH7\Learnphp\routes;

use Exception;
use PH7\Learnphp\Service\User;
use PH7\Learnphp\validation\exception\InvalidValidationException;
use PH7\Learnphp\validation\exception\NotFoundException;


enum UserAction: string
{

case CREATE = 'create ';
 case RETRIEVEAll = 'retrieveAll';
case RETRIEVE = 'retrieve ';
case REMOVE = 'remove ';
case UPDATE = 'update ';


 
public function getResponse(): string{
$userId = $_REQUEST['id'] ?? "null";

$postbody = json_decode(file_get_contents('php://input'));
//    $fname = $postbody['first'];
//    $lname = $postbody['last'];
//    $email = $postbody['email'];
//    $phone = $postbody['phone'] ?? "151115151";

//print_r($postbody);



    //$user = new User($fname." ".$lname, $email, $phone);
    $user = new User();

   try {

       $expectHttpMethod = match($this){
         self::CREATE => Http::POST_METHOD,
           self::RETRIEVEAll => Http::GET_METHOD,
           self::RETRIEVE => Http::GET_METHOD,
           self::REMOVE => Http::DELETE_METHOD,
           self::UPDATE => Http::POST_METHOD,
       };

      if( Http::doesHttpMethodMatch($expectHttpMethod) === false){
          throw new NotFoundException('The requested method is invalid');

      }



        $response = match ($this) {
            self::CREATE => $user->create($postbody),
            // default => $user->retrieveAll(),
            self::RETRIEVEAll => $user->retrieveAll(),
            self::RETRIEVE => $user->retrieve($userId),
            self::UPDATE => $user->update($postbody),
            self::REMOVE => $user->remove($postbody),

        };
    }catch (InvalidValidationException | Exception $e){
       $response = [
           'error' => ['message' => $e->getMessage(),
               'code' => $e->getCode()]
       ];
   }
return json_encode($response);

}

}


$action = $_REQUEST["action"] ?? null;


$userAction = UserAction::tryFrom($action);
if($userAction){
    echo $userAction->getResponse();
}
else {
    require_once 'not-found.routes.php';
}







