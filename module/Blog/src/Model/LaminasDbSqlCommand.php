<?php

namespace Blog\Model;

use RuntimeException;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Adapter\Driver\ResultInterface;
use Laminas\Db\Sql\Delete;
use Laminas\Db\Sql\Insert;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Update;

class LaminasDbSqlCommand implements PostCommandInterface
{
    /**
     * @var AdapterInterface
     */
    private AdapterInterface $db;

    /**
     * @param AdapterInterface $db
     */
    public function __construct(AdapterInterface $db)
    {
        $this->db = $db;
    }

    /**
     * {@inheritDoc}
     */
    public function insertPost(Post $post): Post
    {
        $insert = new Insert('posts');
        $insert->values([
            'title' => $post->getTitle(),
            'text' => $post->getText(),
        ]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $result = $statement->execute();

        if (!$result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during blog post insert operation'
            );
        }

        $id = $result->getGeneratedValue();

        return new Post(
            $post->getTitle(),
            $post->getText(),
            $id
        );
    }

    /**
     * {@inheritDoc}
     */
    public function updatePost(Post $post): Post
    {
    }

    /**
     * {@inheritDoc}
     */
    public function deletePost(Post $post): bool
    {
    }
}
