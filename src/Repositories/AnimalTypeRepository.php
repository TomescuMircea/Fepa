<?php

namespace Src\Repositories;


use Exception;
use Src\Models\ModelAnimalType;

/**
 * Class AnimalTypeRepository
 *
 * This class provides methods for interacting with the 'animaltype' table in the database.
 */
class AnimalTypeRepository extends Repository
{
    /**
     * AnimalTypeRepository constructor.
     *
     * Initializes the AnimalTypeRepository object.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Retrieves an animal type by its type.
     *
     * This method takes a type, queries the 'animaltype' table for a matching record, and returns a ModelAnimalType object.
     *
     * @param string $type The type of the animal.
     * @return ModelAnimalType The animal type object.
     */
    public function getAnimalTypeByType(string $type) : ModelAnimalType
    {
        try {
            $statement = $this->db->prepare('select * from animaltype where Type = ? ');
            $statement->bind_param('s', $type);
            $statement->execute();
        } catch (Exception $e) {
            trigger_error('Error in ' . __METHOD__ . ': ' . $e->getMessage(), E_USER_ERROR);
        }

        $animalType = new ModelAnimalType();
        foreach ($statement->get_result() as $row) {
            $animalType->setId($row['id']);
            $animalType->setType($row['Type']);
        }

        return $animalType;
    }

    /**
     * Retrieves an animal type by its ID.
     *
     * This method takes an ID, queries the 'animaltype' table for a matching record, and returns a ModelAnimalType object.
     *
     * @param int $id The ID of the animal type.
     * @return ModelAnimalType The animal type object.
     */
    public function getAnimalTypeById(int $id) : ModelAnimalType
    {
        try {
            $statement = $this->db->prepare('select * from animaltype where id = ? ');
            $statement->bind_param('i', $id);
            $statement->execute();
        } catch (Exception $e) {
            trigger_error('Error in ' . __METHOD__ . ': ' . $e->getMessage(), E_USER_ERROR);
        }

        $animalType = new ModelAnimalType();
        foreach ($statement->get_result() as $row) {
            $animalType->setId($row['id']);
            $animalType->setType($row['Type']);
        }

        return $animalType;
    }

    /**
     * Submits the report animal form.
     *
     * This method takes the data from the report animal form, creates a new ModelReport object, and attempts to create a new report in the database.
     * If the report is created successfully, it returns the "Report Animal" page. If not, it returns a 500 Internal Server Error status and an error message.
     *
     * @return array The HTTP response status and body content.
     */
    public function getAnimalTypes() : array
    {
        try {
            $statement = $this->db->prepare('select * from animaltype');
            $statement->execute();
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();
        }
        $animalTypes = [];
        foreach ($statement->get_result() as $row) {
            $animalType = new ModelAnimalType();
            $animalType->setId($row['id']);
            $animalType->setType($row['Type']);
            $animalTypes[] = $animalType;
        }
        return $animalTypes;
    }
}