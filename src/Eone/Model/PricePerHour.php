<?php

namespace Eone\Model;

/**
 * Model class for table price_per_hour
 */
class PricePerHour
{
    public const TABLE_NAME = 'price_per_hour';

    public static function deleteFromTo(\PDO $pdo, string $start, string $end): bool
    {
        $sql = "DELETE FROM " . self::TABLE_NAME . " WHERE time >= :start AND time <= :end";
        $sth = $pdo->prepare($sql);

        return $sth->execute(['start' => $start, 'end' => $end]);
    }

    public static function insert(\PDO $pdo, array $data)
    {
        $sql = "INSERT INTO " . self::TABLE_NAME . " (time, price) VALUES (:time, :price)";
        $sth = $pdo->prepare($sql);

        foreach ($data as $hour) {
            $sth->execute(['time' => $hour["Timestamp"], 'price' => $hour["TariffUsage"]]);
        }
    }
}
