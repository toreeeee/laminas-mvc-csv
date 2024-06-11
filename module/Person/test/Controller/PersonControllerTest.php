<?php

namespace PersonTest\Controller;

use Laminas\ServiceManager\ServiceManager;
use Laminas\Stdlib\ArrayUtils;
use Laminas\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Person\Controller\PersonController;
use Person\Model\LaminasDbSqlCommand;
use Person\Model\LaminasDbSqlRepository;
use Person\Model\Person;

class PersonControllerTest extends AbstractHttpControllerTestCase
{
    private $command;

    private $repository;

    protected function configureServiceManager(ServiceManager $services)
    {
        $services->setAllowOverride(true);

        $services->setService("config", $this->updateConfig($services->get("config")));
        $services->setService(LaminasDbSqlCommand::class, $this->mockLaminasDbSqlCommand());
        $services->setService(LaminasDbSqlRepository::class, $this->mockLaminasDbSqrlRepository());

        $services->setAllowOverride(false);
    }

    protected function updateConfig($config)
    {
        $config["db"] = [];

        return $config;
    }

    protected function setUp(): void
    {
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();

        $this->configureServiceManager($this->getApplicationServiceLocator());
    }

    protected function mockLaminasDbSqlCommand(): LaminasDbSqlCommand
    {
        $this->command = $this->createMock(LaminasDbSqlCommand::class);
        return $this->command;
    }

    protected function mockLaminasDbSqrlRepository(): LaminasDbSqlRepository
    {
        $this->repository = $this->createMock(LaminasDbSqlRepository::class);

        return $this->repository;
    }

    public function testIndexActionCanBeAccessed(): void
    {
        $this->dispatch('/person', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('person');
        $this->assertControllerName(PersonController::class); // as specified in router's controller name alias
        $this->assertControllerClass('PersonController');
        $this->assertMatchedRouteName('person');
    }

    public function testAddActionCanBeAccessed(): void
    {
        $this->dispatch('/person/add', 'POST');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('person');
        $this->assertControllerName(PersonController::class); // as specified in router's controller name alias
        $this->assertControllerClass('PersonController');
        $this->assertMatchedRouteName('person/add');
    }

    public function testImportActionCanBeAccessed(): void
    {
        $this->dispatch('/person/import', 'POST');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('person');
        $this->assertControllerName(PersonController::class); // as specified in router's controller name alias
        $this->assertControllerClass('PersonController');
        $this->assertMatchedRouteName('person/import');
    }

    public function testExportActionCanBeAccessed(): void
    {
        $this->dispatch('/person/export', 'POST');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('person');
        $this->assertControllerName(PersonController::class); // as specified in router's controller name alias
        $this->assertControllerClass('PersonController');
        $this->assertMatchedRouteName('person/export');
    }

    public function testEditActionCanBeAccessed(): void
    {
        $this->command->expects($this->once())->method("updatePerson")->with($this->isInstanceOf(Person::class));
        $this->repository->expects($this->once())->method("getById")->with(1);

        $postData = [
            "id" => "1",
            "first_name" => "Max",
            "last_name" => "Mustermann",
            "birthday[month]" => "01",
            "birthday[day]" => "01",
            "birthday[year]" => "2012",
            "salary" => 500
        ];

        $this->dispatch("/person/1/edit", "POST", $postData);
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo("/person");
    }

    public function testDeleteActionCanBeAccessed(): void
    {
        $this->repository->expects($this->once())->method("getById")->with(1);
        $this->command->expects($this->once())->method("deletePerson")->with($this->isInstanceOf(Person::class));

        $postData = [
            "id" => "1",
            "first_name" => "Max",
            "last_name" => "Mustermann",
            "birthday[month]" => "01",
            "birthday[day]" => "01",
            "birthday[year]" => "2012",
            "salary" => 500,
            "confirm" => "Delete"
        ];

        $this->dispatch("/person/1/delete", "POST", $postData);
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo("/person");
    }

    public function testDeleteActionRequireConfirm(): void
    {
        $this->repository->expects($this->once())->method("getById")->with(1);
        $this->command->expects($this->never())->method("deletePerson")->with($this->isInstanceOf(Person::class));

        $postData = [
            "id" => "1",
            "confirm" => "no"
        ];

        $this->dispatch("/person/1/delete", "POST", $postData);
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo("/person");
    }

    // TODO: test import, export, add action
}
