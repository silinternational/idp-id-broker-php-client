<?php
namespace tests;

use Sil\Idp\IdBroker\Client\UserClient;

class UserTest extends \PHPUnit_Framework_TestCase
{
    private function getUserClient($extra = [])
    {
        $testConfig = include __DIR__ . '/config-test.php';
        $config = array_replace_recursive($testConfig, $extra);
        
        return new UserClient($config);
    }
    
    public function testCreate()
    {
        // Arrange:
        $userClient = $this->getUserClient();
        
        // Act:
        $userClient->create();
        
        // Assert:
        $this->fail('Test not yet written.');
    }
    
    public function testDeactivate()
    {
        // Arrange:
        $userClient = $this->getUserClient();
        
        // Act:
        $userClient->deactivate();
        
        // Assert:
        $this->fail('Test not yet written.');
    }
    
    public function testFind()
    {
        // Arrange:
        $userClient = $this->getUserClient();
        
        // Act:
        $userClient->find();
        
        // Assert:
        $this->fail('Test not yet written.');
    }
    
    public function testGet()
    {
        // Arrange:
        $userClient = $this->getUserClient();
        
        // Act:
        $userClient->get();
        
        // Assert:
        $this->fail('Test not yet written.');
    }
    
    public function testList()
    {
        // Arrange:
        $userClient = $this->getUserClient();
        
        // Act:
        $userClient->list();
        
        // Assert:
        $this->fail('Test not yet written.');
    }
    
    public function testUpdate()
    {
        // Arrange:
        $userClient = $this->getUserClient();
        
        // Act:
        $userClient->update();
        
        // Assert:
        $this->fail('Test not yet written.');
    }
}
