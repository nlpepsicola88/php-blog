<?php

declare(strict_types=1);

namespace Blog;

use PDO;


class postMapper
{
    private $connection;

    public function __construct(PDO $connection){
        $this->connection = $connection;
    }

    public function getByUrlKey(string $urlKey): ?array
    {
        $statement = $this->connection->prepare('SELECT * FROM blog_php.post WHERE url_key = :url_key');
        $statement->execute([
            'url_key' => $urlKey
        ]);

        $result = $statement->fetchAll();

        return array_shift($result);
    }
    public function getList(string $direction): ?array
    {
        if (!in_array($direction, ['DESC', 'ASC'])) {
            throw new Exception('The direction is not supported.');
        }
        $statement = $this->connection->prepare('SELECT * FROM blog_php.post ORDER BY published_date '. $direction);

        $statement->execute();

        return $statement->fetchAll();
    }
}

