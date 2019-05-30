<?php

namespace App\Test\Infrastructure\Permission\Loader;

use App\Infrastructure\Permission\Loader\Exception\ActionNotFoundException;
use App\Infrastructure\Permission\Loader\Exception\SubjectNotFoundException;
use App\Infrastructure\Permission\Loader\YamlPermissionLoader;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;
use PHPUnit\Framework\TestCase;

class YamlPermissionLoaderTest extends TestCase
{
    /** @var YamlPermissionLoader */
    private $sut;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $content = <<<'EOF'
# CashRegister Permissions
cash_register:
    open:
        public: false
    close:
        public: false
EOF;

        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('example_dir'));
        vfsStreamWrapper::getRoot()->addChild(vfsStream::newFile('permissions.yaml')->setContent($content));

        $this->sut = new YamlPermissionLoader(
            vfsStream::url('example_dir/permissions.yaml')
        );
    }

    public function testPermissionRetrieving(): void
    {
        $permission = $this->sut->get('cash_register', 'open');

        $this->assertSame([
            'subject' => 'cash_register',
            'action'  => 'open',
            'public'  => false,
        ], $permission);
    }

    public function testPermissionRetrievingWithIncorrectSubject(): void
    {
        $this->expectException(SubjectNotFoundException::class);
        $this->sut->get('test', '');
    }

    public function testPermissionRetrievingWithIncorrectAction(): void
    {
        $this->expectException(ActionNotFoundException::class);
        $this->sut->get('cash_register', 'test');
    }
}
