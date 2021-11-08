<?php

declare(strict_types=1);

namespace Blog;

use PDO;

class LatestPosts{

    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function get(int $limit): ?array
    {
        $statement = $this->connection->prepare('SELECT * FROM blog_php.post '. 'ORDER BY published_date '. 'DESC LIMIT ' . $limit);

        $statement->execute();

        return $statement->fetchAll();
    }
}