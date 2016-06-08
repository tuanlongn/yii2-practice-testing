<?php

use tests\codeception\_pages\TodoPage;

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that delete todo works');
TodoPage::openBy($I);

$I->amGoingTo('try to create new todo');
$I->fillField('title', 'new todo for test delete feature');
$I->click('Add');
$I->expectTo('see \'new todo for test delete feature\' into list');
$I->see('new todo for test delete feature', 'a.todo-item');

$I->amGoingTo('try to delete this todo');
$I->click('x', '.btn-delete');
$I->expectTo('do not see \'new todo for test delete feature\' into list');
$I->dontSee('new todo for test delete feature');