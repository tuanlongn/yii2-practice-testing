<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

use app\models\Todo;

const PAGE_SIZE = 10;

class TodoController extends Controller
{

    public function actionIndex()
    {
        $request = Yii::$app->request;
        $model = new Todo();
        if ($request->post()) {
            $model->title = $request->post('title');
            $model->status = Todo::STATUS_STARTING;
            if ($model->validate()) {
                $model->save();
            }
        }

        $filter = $request->get('filter');
        $filters = [
            'all' => !$filter || $filter === 'all' ? true : false,
            Todo::STATUS_STARTING => $filter === Todo::STATUS_STARTING ? true : false,
            Todo::STATUS_FINISHED => $filter === Todo::STATUS_FINISHED ? true : false
        ];

        $params = [
            'type' => !$filter || $filter === 'all' ? 'all' : 'status',
            'value' => $filter === 'all' ? '' : $filter,
            'paging' => true,
            'pageSize' => PAGE_SIZE
        ];
        $data = Todo::queryBuilderData($params);

        return $this->render('index', ['data' => $data, 'model' => $model, 'filters' => $filters]);
    }

    public function actionUpdate()
    {
        $id = Yii::$app->request->get('id');
        if ($id) {
            Todo::updateData(['id' => $id, 'status' => Todo::STATUS_FINISHED]);
        }
        return $this->redirect('/todo/index');
    }

    public function actionDelete()
    {
        $id = Yii::$app->request->get('id');
        if ($id) {
            Todo::deleteData($id);
        }
        return $this->redirect('/todo/index');
    }
}
