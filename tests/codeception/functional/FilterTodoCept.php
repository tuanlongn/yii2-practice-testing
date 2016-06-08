<?php

use tests\codeception\_pages\TodoPage;

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that filter todo works');
TodoPage::openBy($I);
$I->see('Filter by', 'strong');
$I->see('all', 'a');
$I->see('starting', 'a');
$I->see('finished', 'a');

$I->fillField('title', 'todo 1');
$I->click('Add');
$I->see('todo 1', '.todo-item');
$I->fillField('title', 'todo 2');
$I->click('Add');
$I->see('todo 2', '.todo-item');
$I->click('todo 2', '.todo-item');

$I->amGoingTo('try to filter by starting');
$I->click('starting', 'a');
$I->expectTo('see list todo item have class todo-starting');
$I->SeeElement(['xpath'=>'//a[contains(@class,\'todo-starting\')]']);
$I->dontSeeElement(['xpath'=>'//a[contains(@class,\'todo-finished\')]']);

$I->amGoingTo('try to filter by finished');
$I->click('finished', 'a');
$I->expectTo('see list todo item have class todo-finished');
$I->SeeElement(['xpath'=>'//a[contains(@class,\'todo-finished\')]']);
$I->dontSeeElement(['xpath'=>'//a[contains(@class,\'todo-starting\')]']);

$I->amGoingTo('try to filter by all');
$I->click('all', 'a');
$I->expectTo('see list todo item have both class todo-starting and todo-finished');
$I->SeeElement(['xpath'=>'//a[contains(@class,\'todo-starting\')]']);
$I->SeeElement(['xpath'=>'//a[contains(@class,\'todo-finished\')]']);