<?php

namespace App\Model;

use Nette;

/**
 * Class TableManager
 * Model for providing presenters with data for Bootstrap-tables.
 * @author Petr Zeman
 * @package App\Model
 * @version v1.0
 */
final class TableManager
{
    use Nette\SmartObject;

    /** @var Nette\Database\Context */
    private $database;

    /**
     * GridManager constructor.
     * @param Nette\Database\Context $database
     * @param Passwords $passwords
     */
    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    /**
     * Passes a context of brands to presenter for pagination
     * @param $order - Defines how to have selection ordered
     * @return Nette\Database\Table\Selection - Ordered context of rows from table brand.
     */
    public function getTable($order)
    {
        $table = $this->database->table('brand')->order('brandName ' . $order);
        return $table;
    }

    /**
     * Returns from database a row based on id of the row
     * @param $id - Id of requested row
     * @return Nette\Database\IRow|Nette\Database\Table\ActiveRow|null - Row with id passed
     */
    public function getRowById($id)
    {
        $row = $this->database->table('brand')->where('id=?', $id)->fetch();
        return $row;
    }

    /**
     * Deletes a row from database based on id of the row
     * @param $id - Id of row to be deleted
     */
    public function deleteRow($id)
    {
        $this->database->table('brand')->where('id=?', $id)->delete();
    }

    /**
     * Creates a new row in database based on values
     * @param $values - Values of row to be created
     * @return bool - Check for duplicates - would probably be better through try/catch but i didnt want to make
     * new exception for duplicates
     */
    public function addRow($values)
    {
        if ($this->database->table('brand')->where('brandName=?', $values->brandName)->fetch() == null) {
            $this->database->table('brand')->insert($values);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Updates row in database based on values and id
     * @param $values - Values of row to be created - includes id of row
     * @return bool - Check for duplicates - would probably be better through try/catch but i didnt want to make
     * new exception for duplicates
     */
    public function updateRow($values)
    {
        if ($this->database->table('brand')->where('brandName=?', $values->brandName)->fetch() == null) {
            $this->database->table('brand')->where('id=?', $values->id)
                ->update([
                    'brandName' => $values->brandName

                ]);
            return true;
        } else {
            return false;
        }
    }


}