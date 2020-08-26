<?php

namespace App\Model;

use Nette;

/**
 * Class TableManager
 * Model for providing presenters with data for Bootstrap-tables.
 * @author Petr Zeman
 * @package App\Model
 * @version Summer 2020
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

    public function getTable($order)
    {
        $table = $this->database->table('brand')->order('brandName ' . $order);
        return $table;
    }

    public function getRowById($id)
    {
        $row = $this->database->table('brand')->where('id=?', $id)->fetch();
        return $row;
    }

    public function deleteRow($id)
    {
        $this->database->table('brand')->where('id=?', $id)->delete();
    }

    public function addRow($values)
    {
        if ($this->database->table('brand')->where('brandName=?', $values->brandName)->fetch() == null) {
            $this->database->table('brand')->insert($values);
            return true;
        } else {
            return false;
        }
    }

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