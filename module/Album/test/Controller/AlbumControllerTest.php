<?php

namespace AlbumTest\Controller;

use Album\Controller\AlbumController;
use Album\Model\Album;
use Laminas\Stdlib\ArrayUtils;
use Laminas\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Album\Model\AlbumTable;
use Laminas\ServiceManager\ServiceManager;

class AlbumControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;
    protected $albumTable;

    protected function configureServiceManager(ServiceManager $services): void
    {
        $services->setAllowOverride(true);

        $services->setService('config', $this->updateConfig($services->get('config')));
        $services->setService(AlbumTable::class, $this->mockAlbumTable());

        $services->setAllowOverride(false);
    }

    protected function updateConfig($config)
    {
        $config['db'] = [];
        return $config;
    }

    protected function mockAlbumTable(): AlbumTable
    {
        $this->albumTable = $this->createMock(AlbumTable::class);
        return $this->albumTable;
    }

    protected function setUp(): void
    {
        // The module configuration should still be applicable for tests.
        // You can override configuration here with test case specific values,
        // such as sample view templates, path stacks, module_listener_options,
        // etc.
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
        // Grabbing the full application configuration:
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));
        parent::setUp();

        $this->configureServiceManager($this->getApplicationServiceLocator());
    }

    public function testIndexActionCanBeAccessed()
    {
        $this->albumTable->expects($this->once())
            ->method('fetchAll')
            ->willReturn([]);

        $this->dispatch('/album');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Album');
        $this->assertControllerName(AlbumController::class);
        $this->assertControllerClass('AlbumController');
        $this->assertMatchedRouteName('album');
    }

    public function testAddActionRedirectAfterValidPost()
    {
        $this->albumTable->expects($this->once())
            ->method('saveAlbum')
            ->with($this->isInstanceOf(Album::class));

        $postData = [
            'title' => 'Lez Zeppelin III',
            'artist' => 'Led Zeppelin',
            'id' => '',
            'submit' => "Edit"
        ];

        $this->dispatch('/album/add', 'POST', $postData);
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/album');
    }

    public function testEditActionRedirectAfterValidPost()
    {
        $this->albumTable->expects($this->once())->method("getAlbum")->with(1)->willReturn(new Album());
        $this->albumTable->expects($this->once())
            ->method('saveAlbum')
            ->with($this->isInstanceOf(Album::class));

        $postData = [
            'title' => 'Lez Zeppelin III',
            'artist' => 'Led Zeppelin',
            'id' => '1',
        ];

        $this->dispatch('/album/edit/1', 'POST' , $postData);
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/album');

        // $this->albumTable->expects($this->once())->method("getAlbum")->with($this->isInstanceOf(Album::class));
    }
}