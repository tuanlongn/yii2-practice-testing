<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\data\Pagination;

/**
 * Todo model.
 *
 * @property integer $id
 * @property string $title
 * @property string $status
 */
class Todo extends ActiveRecord
{
    const STATUS_STARTING = 'starting';
    const STATUS_FINISHED = 'finished';

	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'todos';
    }

	/**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['title', 'status'], 'required', 'message' => '{attribute} cannot be blank']
        ];
    }

    /**
     * Find todo item by identify.
     *
     * @param integer
     * @return static|todo or null
     */
    public static function findById($id)
    {
        return static::findOne(['id' => $id]);
    }

    private function _queryByTitle($string)
    {
        return static::find()->filterWhere(['like', 'title', $string]);
    }

    /**
     * Filter todo list by status.
     *
     * @param string
     * @return null or Array
     */
    public static function filterByStatus($status)
    {
        $query = static::_queryByStatus($status);
        return $query->all();
    }

    /**
     * Make query with condition status value.
     *
     * @param $status
     * @return $this
     */
    private static function _queryByStatus($status)
    {
        return static::find()->where(['status' => $status]);
    }

    /**
     * Query builder data.
     *
     * @param array
     * @return array
     */
    public static function queryBuilderData($params)
    {
        if (!$params) return null;
        if (!isset($params['type'])) return null;

        $paging = (isset($params['pageSize'])) ? true : false;

        $query = null;
        switch ($params['type']) {
            case 'all':
                $query = static::find(false);
                break;
            case 'title':
                $query = static::_queryByTitle($params['value']);
                break;
            case 'status':
                $query = static::_queryByStatus($params['value']);
                break;
                default:
                return null;
        }

        // fix order by id DESC
        $query = $query->orderBy(['id' => SORT_DESC]);

        if ($paging) {
            $pagination = new Pagination(['totalCount' => $query->count(), 'pageSize' => $params['pageSize']]);
            $data = $query->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();
            $results = [
                'data' => $data,
                'pagination' => $pagination
            ];
        } else {
            $results = $query->all();
        }

        return $results;
    }

    /**
     * Update todo data.
     *
     * @param $params
     * @return bool
     */
    public static function updateData($params)
    {
        if (!$params || !isset($params['id'])) return false;

        $todo = static::findById($params['id']);
        if (!$todo) return false;

        foreach ($params as $key => $val) {
            if (!$todo->hasAttribute($key)) return false;
            else $todo[$key] = $val;
        }

        return $todo->save();
    }

    /**
     * Delete todo Data.
     *
     * @param $id
     * @return bool|false|int
     * @throws \Exception
     */
    public static function deleteData($id)
    {
        if (!$id) return false;
        $todo = static::findById($id);
        if (!$todo) return false;
        if (!$todo->delete()) return false;
        return true;
    }

}