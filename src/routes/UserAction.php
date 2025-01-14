<?php
namespace PH7\Learnphp\routes;

use Exception;
use PH7\Learnphp\endpoints\User;
use PH7\Learnphp\validation\exception\InvalidValidationException;

require_once dirname(__DIR__) ."/endpoints/User.php";


enum UserAction: string
{

case CREATE = 'create ';
 case RETRIEVEAll = 'retrieveAll';
case RETRIEVE = 'retrieve ';
case REMOVE = 'remove ';
case UPDATE = 'update ';


 
public function getResponse(): string{
$userId = $_GET['user_id'] ?? "null";

$postbody = json_decode(file_get_contents('php://input'));
//    $fname = $postbody['first'];
//    $lname = $postbody['last'];
//    $email = $postbody['email'];
//    $phone = $postbody['phone'] ?? "151115151";

//print_r($postbody);



    //$user = new User($fname." ".$lname, $email, $phone);
    $user = new User(" arman", "kumar", "62622611");

   try {
        $response = match ($this) {
            self::CREATE => $user->create($postbody),
            // default => $user->retrieveAll(),
            self::RETRIEVEAll => $user->retrieveAll(),
            self::RETRIEVE => $user->retrieve($userId),
            self::UPDATE => $user->update($postbody),
            self::REMOVE => $user->remove($userId),

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


$action = $_GET["action"] ?? null;


$useraction = match($action){
        'create' => UserAction::CREATE,
    'retrieve' => UserAction::RETRIEVE,
    'default' => UserAction::RETRIEVEAll,
    'remove' => UserAction::REMOVE,
    'update' => UserAction::UPDATE,
};



echo $useraction->getResponse();




// $userAction = match ($action) {
//     'create' => UserAction::CREATE,
//     'retrieve' => UserAction::RETRIEVE,
//     'default' => UserAction::RETRIEVEAll,
//     'remove' => UserAction::REMOVE,
//     'update' => UserAction::UPDATE,
// };