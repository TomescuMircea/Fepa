<?php

namespace Src\Repositories;

use Exception;
use Src\Models\ModelTag;

/**
 * Class TagRepository
 *
 * This class provides methods for interacting with the 'tags' table in the database.
 */
class TagRepository extends Repository
{
    /**
     * TagRepository constructor.
     *
     * Initializes the TagRepository object.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Creates a new tag.
     *
     * This method takes a ModelTag object, extracts the text, and attempts to create a new record in the 'tags' table.
     * If the record is created successfully, it returns the ID of the new tag. If not, it throws an error.
     *
     * @param ModelTag $tag The tag to create.
     * @return int The ID of the new tag.
     * @throws Exception if there is an error with the database operation.
     */
    public function createTag($tag)
    {
        try {
            $statement = $this->db->prepare('insert into tags (Text) VALUES(?)');
            $text = $tag->getText();
            $statement->bind_param('s', $text);
            $statement->execute();

            return $statement->insert_id;
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();
        }
    }

    /**
     * Retrieves a tag by its text.
     *
     * This method takes a text string, queries the 'tags' table for a matching record, and returns a ModelTag object.
     *
     * @param string $text The text of the tag.
     * @return ModelTag The tag object.
     * @throws Exception if there is an error with the database operation.
     */
    public function getTag(string $text) : ModelTag
    {
        $text = strtolower($text);
        try {
            $statement = $this->db->prepare('select * from tags where Text = ? ');
            $statement->bind_param('s', $text);
            $statement->execute();
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();
        }

        $tag = new ModelTag();
        foreach ($statement->get_result() as $row) {
            $tag->setId($row['id']);
            $tag->setText($row['Text']);
        }

        return $tag;
    }

    /**
     * Retrieves all tags.
     *
     * This method queries the 'tags' table for all records and returns an array of ModelTag objects.
     *
     * @return array The array of tag objects.
     * @throws Exception if there is an error with the database operation.
     */
    public function getTags() : array
    {
        try {
            $statement = $this->db->prepare('select * from tags');
            $statement->execute();
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();
        }

        $tags = [];
        foreach ($statement->get_result() as $row) {
            $tag = new ModelTag();
            $tag->setId($row['id']);
            $tag->setText($row['Text']);
            $tags[] = $tag;
        }

        return $tags;
    }

    public function getAllTags() : array
    {
        try {
            $statement = $this->db->prepare('select * from tags');
            $statement->execute();
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();
        }
        $tags = [];
        foreach ($statement->get_result() as $row) {
            $tag = new ModelTag();
            $tag->setId($row['id']);
            $tag->setText($row['Text']);
            $tags[] = $tag;
        }
        return $tags;
    }
}