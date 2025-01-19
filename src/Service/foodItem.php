<?php

namespace PH7\Learnphp\Service;

use PH7\JustHttp\StatusCode;

use PH7\Learnphp\DAL\foodItemDal;
use PH7\Learnphp\validation\exception\InvalidValidationException;
use PH7\PhpHttpResponseHeader\Http;
use Respect\Validation\Validator as v;


class foodItem{
public function retrieveAll(): array{

    $Items = foodItemDal::getAll();

    if(count($Items) === 0){
        foodItemDal::createDefaultItem();
        $Items = foodItemDal::getAll();

    }

    return array_map(function (object $data){
        unset($data['id']);
        return $data;
    }, $Items);

}

    public function retrieve(string $foodId): ?array{


        if(v::uuid(version: 4)->validate($foodId)){
            $data = foodItemDal::get($foodId);
            unset($data['id']);
            return $data;
        }
        Http::setHeadersByCode(StatusCode::NOT_FOUND);

        throw new InvalidValidationException("invalid UUID");
    }


}