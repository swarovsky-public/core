<?php

namespace Swarovsky\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Swarovsky\Core\Helpers\StrHelper;

class AdvancedModel extends Model
{
    private array $foreign_keys = [];
    private array $custom_columns = [];
    public array $column_order = [];
    protected array $has_many_relations = [];
    protected array $belongs_to_many_relations = [];

    public function custom_sync(Request $request): void{

    }

    public function getSchema(): array
    {
        $fieldAndTypeList = [];
        $databaseName = $this->getConnection()->getDatabaseName();
        $foreign_keys = DB::select('SELECT
            COLUMN_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = :database
            AND TABLE_NAME  = :table
        ', ['database' => $databaseName, 'table' => $this->getTable()]);
        $this->foreign_keys = $foreign_keys;
        //dd($foreign_keys, DB::select("describe " . $this->getTable()));
        foreach (DB::select("describe " . $this->getTable()) as $field) {
            $type = explode(' ', $field->Type)[0];
            $max_len = -1;
            if (strpos($type, '(') !== false) {
                [$type, $max_len] = str_replace(')', '', explode('(', $type));
            }
            $required = $field->Null === 'NO';
            $unique = $field->Key === 'UNI';
            $ai = $field->Extra === 'auto_increment';

            $foreign_key = null;
            if ($field->Key === 'MUL') {
                foreach ($foreign_keys as $fk) {
                    if ($fk->COLUMN_NAME === $field->Field) {
                        $foreign_key = [
                            'table' => $fk->REFERENCED_TABLE_NAME,
                            'column' => $fk->REFERENCED_COLUMN_NAME
                        ];
                        break;
                    }
                }
            }

            $fieldAndTypeList[$field->Field] = [
                'required' => $required,
                'type' => $type,
                'max-length' => $max_len,
                'unique' => $unique,
                'ai' => $ai,
                'foreign_key' => $foreign_key
            ];
        }

        return $fieldAndTypeList;
    }

    public function getForeignKeys(): array
    {
        return $this->foreign_keys;
    }

    public function setForeignKeys(array $foreign_keys): void
    {
        $this->foreign_keys = $foreign_keys;
    }

    public function getCustomColumns(): array
    {
        return $this->custom_columns;
    }

    public function setCustomColumns(array $customColumns): void
    {
        $this->custom_columns = $customColumns;
    }

    public function getOrderedColumns(): array
    {
        $orderedColumns = [];
        foreach ($this->column_order as $column => $position) {
            $orderedColumns[$position] = $column;
        }
        foreach ($this->getCustomColumns() as $column => $customColumn) {
            $orderedColumns[$customColumn['position']] = [$column, $customColumn['content']];
        }
        foreach ($this->getSchema() as $column => $value) {
            $notFound = true;
            foreach ($orderedColumns as $orderedColumn) {
                if (is_array($orderedColumn) && $orderedColumn[0] === $column) {
                    $notFound = false;
                    break;
                }
                if (!is_array($orderedColumn) && $orderedColumn === $column) {
                    $notFound = false;
                    break;
                }
            }
            if($notFound){
                $orderedColumns[self::firstFreeKey($orderedColumns)] = $column;
            }
        }
        ksort($orderedColumns);
        return $orderedColumns;
    }

    protected static function firstFreeKey($array): int
    {
        $i = 0;
        while (isset($array[$i])) {
            $i++;
        }
        return $i;
    }

    public function getHasManyRelations(): array{
        return $this->has_many_relations;
    }

    public function getBelongsToManyRelations(): array{
        return $this->belongs_to_many_relations;
    }
}
