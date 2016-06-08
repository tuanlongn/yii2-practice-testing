<?php

use tests\codeception\_pages\TodoPage;

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that create todo works');
TodoPage::openBy($I);
$I->see('Add new Todo', 'strong');

$I->amGoingTo('try to create new todo with a blank title');
$I->fillField('title', '');
$I->click('Add');
$I->expectTo('see validations errors');
$I->see('Title cannot be blank', '.alert');

$I->amGoingTo('try to create new todo with a title: \'this is a new task\'');
$I->fillField('title', 'this is a new task');
$I->click('Add');
$I->expectTo('see task \'this is a new task\' in task list');
$I->see('this is a new task', '.todo-item');
