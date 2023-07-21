<?php

namespace System\Db;

use PDOStatement;

class MysqlQuery
{
    private Connect $connect;

    public function __construct()
    {
        $this->connect = new Connect();
    }

    /**
     * $condition format: [
     *      'columns' => [
     *          nameColumn1 => value1,
     *          nameColumn2 => value2,
     *          ...
     *      ],
     *      'limit' => [
     *          'offset' => integer,
     *          'count' => integer,
     *      ],
     *      'order' => [
     *          'nameColumn' => 'DESC' | 'ASK',
     *          'nameColumn2' => 'DESC' | 'ASK',
     *          ...
     *      ],
     * ]
     *
     * @throws DbException
     */
    public function select(string $tableName, array $condition): bool|PDOStatement
    {
        $sql = 'SELECT * FROM ' . $tableName;

        $sqlParams = [];

        $columns = $condition['columns'] ?? null;
        if (!is_null($columns) && !empty($columns)) {
            $params = $this->generateParams($columns);
            $sqlParams = $params['paramsSql'];
            $sqlWhere = implode(', ', $params['strsSql']);
            $sql .=  ' WHERE ' . $sqlWhere ;
        }

        if (isset($condition['order'])) {
            $orderParams = [];
            foreach ($condition['order'] as $column => $order) {
                $orderParams[] = $column . ' ' . $order;
            }
            if (!empty($orderParams)) {
                $strOrderParams = implode(',', $orderParams);
                $sql .= ' ORDER BY ' . $strOrderParams;
            }
        }

        if (isset($condition['limit'])) {
            $limit = $condition['limit'];
            $sqlLimit = (int) $limit['count'];
            if (isset($limit['offset'])) {
                $sqlLimit = (int) $limit['offset'] . ', ' . $sqlLimit;
            }
            $sql .= ' LIMIT ' . $sqlLimit;
        }

        $sql .= ';';

        return $this->execute($sql, $sqlParams);
    }

    /**
     * $values format: [
     *      nameColumn1 => value1,
     *      nameColumn2 => value2,
     *      ....
     * ]
     *
     * @throws DbException
     */
    public function insert(string $tableName, array $attributeValues): bool|PDOStatement
    {
        $params = $this->generateParams($attributeValues);

        $sqlColumns = implode(',', array_keys($attributeValues));

        $sqlParamValues = implode(',', array_keys($params['paramsSql']));

        $sql = 'INSERT INTO ' . $tableName . ' (' . $sqlColumns . ') VALUES (' . $sqlParamValues . ');';

        $sqlParams = $params['paramsSql'];

        return $this->execute($sql, $sqlParams);
    }

    /**
     * $attributeValues format: [
     *      nameColumn1 => value1,
     *      nameColumn2 => value2,
     *      ...
     * ]
     *
     * @throws DbException
     */
    public function update(string $tableName, string $namePrimaryKey, array $attributeValues): bool|PDOStatement
    {
        $params = $this->generateParams($attributeValues);

        $strSqlParamsAttributes = implode(', ', $params['strsSql']);

        $sqlParams = $params['paramsSql'];

        $sqlParams[':id'] = $attributeValues[$namePrimaryKey];
        $sqlWhere = $namePrimaryKey . '=:id';

        $sql = 'UPDATE ' . $tableName . ' SET ' . $strSqlParamsAttributes . ' WHERE ' . $sqlWhere . ';';

        return $this->execute($sql, $sqlParams);
    }

    /**
     * $values format: [
     *      nameParam1 => value1,
     *      nameParam2 => value2,
     *      ....
     * ]
     *
     * return format: [
     *          'values => [
     *          nameColumn1 => [
     *              'param' = nameParamValue1,
     *              'val' = value1,
     *              ...
     *          ],
     *          nameColumn2 => [
     *              'param' = nameParamValue2,
     *              'val' = value2,
     *              ...
     *          ],
     *          ......
     *      ],
     *      'strsSql' => [
     *          [0] => 'nameColumn1=nameParamValue1',
     *          [1] => 'nameColumn2=nameParamValue2',
     *          ...
     *      ]
     *      'paramsSql' => [
     *          nameParamValue1 => value1,
     *          nameParamValue2 => value2,
     *          ...
     *      ]
     * ]
     *
     * @param array $values
     * @return array
     */
    private function generateParams(array $values): array
    {
        $params = [];
        $i = 0;

        foreach ($values as $column => $value) {
            $nameParam = ':v' . $i;
            $params['values'][$column]['nameParam'] = $nameParam;
            $params['values'][$column]['value'] = $value;

            $params['strsSql'][] = $column . '=' . $nameParam;
            $params['paramsSql'][$nameParam] = $value;
            $i++;
        }

        return $params;
    }

    /**
     * $params format: [
     *      nameParam1 => value1,
     *      nameParam2 => value2,
     *      ....
     * ]
     *
     * @param PDOStatement $query
     * @param array $params
     * @return bool
     */
    public function bindParam(PDOStatement $query, array $params): bool
    {
        foreach ($params as $nameParam => $value) {
            if (!$query->bindValue($nameParam, $value))
                return false;
        }
        return true;
    }

    /**
     * @throws DbException
     */
    public function execute(string $sql, array $params): bool|PDOStatement
    {
        $query = $this->connect->prepare($sql);

        if ($query === false || !$this->bindParam($query, $params)) {
            throw new DbException('Не удалось выполнить запрос');
        }

        $result = $query->execute();

        if (!$result) {
            throw new DbException($query->errorInfo()[2]);
        }

        return $result ? $query : $result;
    }

    /**
     * @throws DbException
     */
    public function count(string $tableName, array $condition = []): bool|PDOStatement
    {
        $sql = 'SELECT COUNT(*) FROM ' . $tableName;

        $sqlParams = [];

        if (!empty($condition)) {
            $params = self::generateParams($condition);
            $sqlParams = $params['paramsSql'];
            $sqlWhere = implode(', ', $params['strsSql']);
            $sql .=  ' WHERE ' . $sqlWhere ;
        }

        return $this->execute($sql, $sqlParams);
    }

    public function getConnect(): Connect
    {
        return $this->connect;
    }
}