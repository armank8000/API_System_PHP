<?php

namespace PH7\Learnphp\DAL;

use PH7\JustHttp\StatusCode;
use PH7\PhpHttpResponseHeader\Http;
use Ramsey\Uuid\Uuid;
use RedBeanPHP\R;
use RedBeanPHP\RedException\SQL;

class foodItemDal
{
    public const TABLE_NAME = 'fooditem';

    public static function get(string $foodUuid): ?array{
        $data= R::findOne(self::TABLE_NAME, 'item_uuid = :foodUuid', ['foodUuid' => $foodUuid]);
        return $data->export();
    }
    public static function getAll(): array{
        return R::findAll(self::TABLE_NAME);

    }

    public static function createDefaultItem():void{
        $itemBeam = R::dispense(self::TABLE_NAME);
        $itemBeam->item_uuid = (string)Uuid::uuid4();
        $itemBeam->item_name = 'sample';
        $itemBeam->price = "$65.1";
        $itemBeam->available = true;
        try{
             R::store($itemBeam);

        }catch (SQL $exception){
            Http::setHeadersByCode(StatusCode::BAD_REQUEST);
        }finally{
            R::close();
        }

    }

}