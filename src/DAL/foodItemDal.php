<?php

namespace PH7\Learnphp\DAL;

use PH7\JustHttp\StatusCode;
use PH7\Learnphp\Entity\Item as ItemEntity;
use PH7\PhpHttpResponseHeader\Http;
use RedBeanPHP\R;
use RedBeanPHP\RedException\SQL;

class foodItemDal
{
    public const TABLE_NAME = 'fooditem';

    public static function get(string $foodUuid): ItemEntity{
        $data= R::findOne(self::TABLE_NAME, 'item_uuid = :foodUuid', ['foodUuid' => $foodUuid]);
        return (new ItemEntity())->unSerialize($data?->export());
    }
    public static function getAll(): array{
        $itemBean = R::findAll(self::TABLE_NAME);
        $areAnyItem = $itemBean && count($itemBean);

        if (!$areAnyItem) {
            return [];
        }

        return array_map(function (object $itemBean): array {
$itemEntity = (new ItemEntity())->unSerialize($itemBean->export());

return [
    'item_uuid' => $itemEntity->getItemUuid(),
    'name' => $itemEntity->getName(),
    'price' => $itemEntity->getPrice(),
    'available' => $itemEntity->getAvailable(),
];
        },$itemBean);
    }

    public static function createDefaultItem(ItemEntity $itemEntity):int|string|false{

        $itemBeam = R::dispense(self::TABLE_NAME);
        $itemBeam->item_uuid = $itemEntity->getItemUuid();
        $itemBeam->item_name = $itemEntity->getName();
        $itemBeam->price = $itemEntity->getPrice();
        $itemBeam->available = $itemEntity->getAvailable();
        try{
            return R::store($itemBeam);

        }catch (SQL){
            Http::setHeadersByCode(StatusCode::BAD_REQUEST);
        }finally{
            R::close();
        }
return false;
    }

}