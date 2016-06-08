<?php
namespace tests\codeception\unit\fixtures;

use yii\test\ActiveFixture;

class TodoFixture extends ActiveFixture
{
	public $modelClass = 'app\models\Todo';
}