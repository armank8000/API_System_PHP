<?php

namespace PH7\Learnphp\Service;

use PH7\JustHttp\StatusCode;

use PH7\Learnphp\DAL\foodItemDal;
use PH7\Learnphp\Entity\Item as ItemEntity;
use PH7\Learnphp\validation\exception\InvalidValidationException;
use PH7\PhpHttpResponseHeader\Http;
use Ramsey\Uuid\Uuid;
use Respect\Validation\Validator as v;


class foodItem{
public function retrieveAll(): array{

    $Items = foodItemDal::getAll();

    if(count($Items) === 0){

        $itemUuid = Uuid::uuid4()->toString();
        $itemEntity = new ItemEntity();
        $itemEntity->setItemUuid($itemUuid);
        $itemEntity->setName('sample item');
        $itemEntity->setPrice(100);
        $itemEntity->setAvailable(true);

        if(foodItemDal::createDefaultItem($itemEntity)){
            $Items = foodItemDal::getAll();
        }else{
            $Items = [];
        }
    }

    return $Items;

}

    public function retrieve(string $foodId): array{


        if($foodId){
            if (v::uuid(version: 4)->validate($foodId)) {
               if( $data = foodItemDal::get($foodId)){
                   if($data->getItemUuid()){
                       return [
                           'uuid' => $data->getItemUuid(),
                           'name' => $data->getName(),
                           'price' => $data->getPrice(),
                           'available' => $data->getAvailable(),
                       ];
                   }
               }
                Http::setHeadersByCode(StatusCode::NOT_FOUND);

                throw new InvalidValidationException("Invalid foodId");

            }
            Http::setHeadersByCode(StatusCode::BAD_REQUEST);

            throw new InvalidValidationException("Please check your uuid");

        }
        Http::setHeadersByCode(StatusCode::BAD_REQUEST);

        throw new InvalidValidationException("Please check your uuid");
    }


}