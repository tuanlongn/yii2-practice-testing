<?php

use tests\codeception\_pages\TodoPage;

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that change todo status works');
TodoPage::openBy($I);

$I->amGoingTo('try to create new todo');
$I->fillField('title', 'new todo');
$I->click('Add');
$I->expectTo('see \'new todo\' into a have class \'todo-starting\'');
$I->see('new todo', 'a.todo-starting');

$I->amGoingTo('try to change new todo to finished');
$I->click('new todo', 'a.todo-starting');
$I->expectTo('see \'new todo\' into a class \'todo-finished\'');
$I->see('new todo', '.todo-finished');