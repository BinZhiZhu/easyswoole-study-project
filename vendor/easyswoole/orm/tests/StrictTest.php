<?php
/**
 * 严格模式
 * User: haoxu
 * Date: 2019-10-30
 * Time: 18:07
 */

namespace EasySwoole\ORM\Tests;


use EasySwoole\ORM\Db\Config;
use EasySwoole\ORM\Db\Connection;
use EasySwoole\ORM\DbManager;
use PHPUnit\Framework\TestCase;


use EasySwoole\ORM\Tests\models\TestUserListModel;

class StrictTest extends TestCase
{
    /**
     * @var $connection Connection
     */
    protected $connection;
    protected $tableName = 'user_test_list';

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $config = new Config(MYSQL_CONFIG);
        $this->connection = new Connection($config);
        DbManager::getInstance()->addConnection($this->connection);
        $connection = DbManager::getInstance()->getConnection();
        $this->assertTrue($connection === $this->connection);
    }

    public function testStrict()
    {
        $testUserModel = new TestUserListModel();
        $testUserModel->state = 1;
        $testUserModel->name = 'Siam';
        $testUserModel->age = 18;
        $testUserModel->addTime = date('Y-m-d H:i:s');
        $testUserModel->strict = 'strict';
        $array = $testUserModel->toArray();
        $this->assertArrayNotHasKey('strict', $array);
    }

    public function testUnStrict()
    {
        $testUserModel = new TestUserListModel();
        $testUserModel->state = 1;
        $testUserModel->name = 'Siam';
        $testUserModel->age = 18;
        $testUserModel->addTime = date('Y-m-d H:i:s');
        $testUserModel->strict = 'strict';
        $array = $testUserModel->toArray(false, false);
        $this->assertEquals($array['strict'], 'strict');
    }

    // 插入过滤数据
    public function testSaveFilter()
    {
        $model = TestUserListModel::create([
            'state' => 1,
            'name' => 'Siam',
            'age' => 18,
            'addTime' => date('Y-m-d H:i:s'),
            'strict' => 'error',
        ]);
        $test = $model->save();
        $this->assertIsInt($test);
    }

    // 更新过滤数据
    public function testUpdateFilter()
    {
        $test = TestUserListModel::create()->get([
            'state' => 1,
            'name' => 'Siam',
            'age' => 18,
            'addTime' => date('Y-m-d H:i:s'),
        ]);

        $res = $test->update([
            'age' => 19,
            'strict' => 'error'
        ]);

        $this->assertTrue($res);
        $this->assertEquals($test->age, 19);
    }

    public function testDeleteAll()
    {
        $res = TestUserListModel::create()->destroy(null, true);
        $this->assertIsInt($res);
    }

}