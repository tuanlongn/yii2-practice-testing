<?php

use yii\codeception\DbTestCase;

use app\models\Todo;
use tests\codeception\unit\fixtures\TodoFixture;

/**
 * Class test Todo model.
 */
class TodoTest extends DbTestCase
{
    use Codeception\Specify;

    public function fixtures()
    {
        return [
            'todo' => [
                'class' => TodoFixture::className(),
                'dataFile' => '@tests/codeception/unit/fixtures/data/models/todo.php'
            ],
        ];
    }

    protected function setUp()
    {
        $this->loadFixtures();
    }

    protected function tearDown()
    {
        $this->unloadFixtures();
    }

    // tests
    public function testConstants()
    {
        $this->assertEquals(Todo::STATUS_STARTING, 'starting');
        $this->assertEquals(Todo::STATUS_FINISHED, 'finished');
    }

    public function testValidator()
    {
        $model = new Todo();

        $model->attributes = $this->todo['HasTitleNull'];
        $this->specify('validate should be false if title is null', function() use ($model) {
            $this->assertFalse($model->validate());

            $this->specify('and should have validation error title', function() use ($model) {
                $this->assertTrue($model->hasErrors('title'));
            });
        });

        $model->attributes = $this->todo['HasStatusNull'];
        $this->specify('validate should be false if status is null', function() use ($model) {
            $this->assertFalse($model->validate());

            $this->specify('and should have validation error status', function() use ($model) {
                $this->assertTrue($model->hasErrors('status'));
            });
        });
        
        $model->attributes = $this->todo['HasFullData'];
        $this->specify('validate should be true is title and status is not null', function() use ($model) {
            $this->assertTrue($model->validate());
        });
    }

    public function testInsertNewSuccess()
    {
        $model = new Todo();
        $model->attributes = $this->todo['HasFullData'];
        $this->assertTrue($model->save());
    }

    public function testUpdateData()
    {
        $this->specify('should be return false if param is null or empty', function() {
            $this->assertFalse(Todo::updateData([]));
            $this->assertFalse(Todo::updateData(''));
        });

        $this->specify('should be return false if param has not id', function() {
            $this->assertFalse(Todo::updateData(['other' => 'value']));
        });

        $this->specify('should be return false if non exist', function() {
            $this->assertFalse(Todo::updateData(['id' => 999999]));
        });

        $this->specify('should be return false if param have key is not property', function() {
            $todo = Todo::findOne(['status' => Todo::STATUS_STARTING]);
            $this->assertFalse(Todo::updateData(['id' => $todo->id, 'other' => 'value']));
        });

        $this->specify('should be return true if param correct', function() {
            $todo = Todo::findOne(['status' => Todo::STATUS_STARTING]);
            $this->assertTrue(Todo::updateData(['id' => $todo->id, 'title' => 'update', 'status' => Todo::STATUS_FINISHED]));
        });
    }

    public function testDeleteData()
    {
        $this->specify('should be return false if param id null or incorrect', function() {
            $this->assertFalse(Todo::deleteData(null));
            $this->assertFalse(Todo::deleteData(''));
            $this->assertFalse(Todo::deleteData(999999));
        });

        $this->specify('should be return true if param id correct', function() {
            $todo = Todo::findOne(['status' => Todo::STATUS_STARTING]);
            $this->assertTrue(Todo::deleteData($todo->id));
        });
    }

    public function testFindById()
    {
        $model = new Todo();
        $model->attributes = $this->todo['HasFullData'];
        $this->assertTrue($model->save());

        $todo = Todo::findById($model->id);
        $this->assertEquals($todo->title, $model->title);
    }

    public function testFilterByStatus()
    {
        $status = Todo::STATUS_STARTING;
        $todos = Todo::filterByStatus($status);
        $this->assertNotEmpty($todos);

        foreach ($todos as $todo) {
            $this->assertEquals($todo->status, $status);
        }
    }

    public function testQueryBuilderData()
    {
        $this->specify('should return null if param is not array or emty', function() {
            $results = Todo::queryBuilderData([]);
            $this->assertNull($results);

            $results = Todo::queryBuilderData('');
            $this->assertNull($results);
        });

        $this->specify('should return null if param have not type key', function() {
            $results = Todo::queryBuilderData(['other' => 'value']);
            $this->assertNull($results);
        });

        $this->specify('should return null if param type is not supported', function() {
            $results = Todo::queryBuilderData(['type' => 'xyz']);
            $this->assertNull($results);
        });

        $this->specify('should return results is array instance of itself if param has not pageSize', function() {
            $results = Todo::queryBuilderData(['type' => 'all']);
            $this->assertTrue(is_array($results));
            $this->assertArrayNotHasKey('pagination', $results);
            $this->assertEquals(count($results), count($this->todo));
            foreach ($results as $result) {
                $this->assertInstanceOf(Todo::class, $result);
            }
        });

        $this->specify('should return results have data and pagination key if param has pageSize', function() {
            $pageSize = 10;
            $results = Todo::queryBuilderData(['type' => 'all', 'pageSize' => $pageSize]);
            $this->assertArrayHasKey('data', $results);
            $this->assertArrayHasKey('pagination', $results);
        });

        $this->specify('should may be search by title', function() {
            $results = Todo::queryBuilderData(['type' => 'title', 'value' => 'task']);
            $this->assertNotEmpty($results);
            foreach ($results as $result) {
                $this->assertContains('task', $result->title);
            }
        });

        $this->specify('should may be search by status', function() {
            $results = Todo::queryBuilderData(['type' => 'status', 'value' => Todo::STATUS_STARTING]);
            $this->assertNotEmpty($results);
            foreach ($results as $result) {
                $this->assertEquals($result->status, Todo::STATUS_STARTING);
            }
        });
    }
}
