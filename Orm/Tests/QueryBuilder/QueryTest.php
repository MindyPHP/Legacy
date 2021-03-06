<?php

/**
 * All rights reserved.
 * 
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 04/01/14.01.2014 03:04
 */

namespace Mindy\Orm\Tests\QueryBuilder;

use Modules\Tests\Models\Customer;
use Modules\Tests\Models\Group;
use Modules\Tests\Models\Membership;
use Modules\Tests\Models\User;
use Mindy\Orm\Tests\OrmDatabaseTestCase;

abstract class QueryTest extends OrmDatabaseTestCase
{
    public $prefix = '';

    protected function getModels()
    {
        return [new User, new Group, new Membership, new Customer];
    }

    public function testGet()
    {
        $this->assertTrue((new User(['username' => 'foo']))->save());
        $user = User::objects()->get(['pk' => 1]);
        $this->assertEquals('foo', $user->username);

        $sql = User::objects()->asArray()->getSql(['pk' => 1]);
        $this->assertSql("SELECT [[user_1]].* FROM [[user]] AS [[user_1]] WHERE ([[user_1]].[[id]]=1)", $sql);
        $this->assertEquals([
            'id' => 1,
            'username' => 'foo',
            'password' => ''
        ], User::objects()->asArray()->get(['pk' => 1]));
    }

    public function testFindWhere()
    {
        $this->assertTrue((new User(['username' => 'foo']))->save());
        $qs = User::objects();
        $this->assertEquals(1, $qs->filter(['username' => 'foo'])->count());
        $this->assertEquals([[
            'id' => 1,
            'username' => 'foo',
            'password' => ''
        ]], $qs->asArray()->all());
    }

    public function testExclude()
    {
        $this->assertTrue((new User(['username' => 'foo']))->save());
        $this->assertTrue((new User(['username' => 'bar']))->save());
        $qs = User::objects()->filter(['username' => 'foo'])->exclude(['username' => 'bar']);
        $this->assertEquals(1, $qs->count());
        $this->assertSql("SELECT COUNT(*) FROM [[user]] AS [[user_1]] WHERE (([[user_1]].[[username]]=@foo@)) AND ((NOT ([[user_1]].[[username]]=@bar@)))", $qs->countSql());
    }

    public function testOrExclude()
    {
        $this->assertTrue((new User(['username' => 'foo']))->save());
        $this->assertTrue((new User(['username' => 'bar']))->save());
        $qs = User::objects();
        $this->assertEquals(2, $qs->count());
        $qs->exclude(['username' => 'foo'])->orExclude(['username' => 'bar']);
        $this->assertSql(
            "SELECT COUNT(*) FROM [[user]] AS [[user_1]] WHERE ((NOT ([[user_1]].[[username]]=@foo@))) OR ((NOT ([[user_1]].[[username]]=@bar@)))",
            $qs->countSql()
        );
    }

    public function testExactQs()
    {
        $this->assertTrue((new User(['username' => 'foo']))->save());
        $this->assertTrue((new User(['username' => 'bar']))->save());
        $this->assertTrue((new Customer(['address' => 'bar', 'user_id' => 1]))->save());
        $this->assertTrue((new Group(['name' => 'example']))->save());

        $group = Group::objects()->get(['pk' => 1]);
        $user1 = User::objects()->get(['pk' => 1]);
        $user2 = User::objects()->get(['pk' => 2]);
        $this->assertEquals(1, $user1->pk);
        $this->assertEquals(2, $user2->pk);
        $group->users->link($user1);
        $group->users->link($user2);

        $links = Membership::objects()->asArray()->all();
        $this->assertEquals([
            ['id' => '1', 'group_id' => '1', 'user_id' => '1'],
            ['id' => '2', 'group_id' => '1', 'user_id' => '2'],
        ], $links);

        $this->assertEquals(1, Group::objects()->count());

        $this->assertEquals(1, $user1->groups->count());
        $this->assertEquals(1, $user2->groups->count());
        $this->assertEquals(2, $group->users->count());

        $user = User::objects()->filter(['pk' => 1])->get();
        $customer = Customer::objects()->filter(['user' => $user])->get();
        $this->assertEquals(1, $customer->pk);

        $user = User::objects()->get(['pk' => 1]);
        $customer = Customer::objects()->get(['user' => $user]);
        $this->assertEquals(1, $customer->pk);
    }
}
