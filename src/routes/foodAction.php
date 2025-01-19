<?php
namespace PH7\Learnphp\routes;

use Exception;
use PH7\Learnphp\Service\foodItem;
use PH7\Learnphp\validation\exception\InvalidValidationException;




enum foodAction: string
{


 case RETRIEVEAll = 'retrieveAll';
case RETRIEVE = 'retrieve ';

public function getResponse(): string{
$foodId = $_REQUEST['id'] ?? "null";

$postbody = json_decode(file_get_contents('php://input'));





    $item = new foodItem();

   try {
        $response = match ($this) {
            // default => $user->retrieveAll(),
            self::RETRIEVEAll => $item->retrieveAll(),
            self::RETRIEVE => $item->retrieve($foodId),

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


$foodAction = foodAction::tryFrom($action);
if($foodAction){
    echo $foodAction->getResponse();
}
else {
    require_once 'not-found.routes.php';
}







