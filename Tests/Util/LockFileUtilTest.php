<?php

namespace Ip\MeatUp\Tests\Util;

use Ip\MeatUp\Util\LockFileUtil;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

class LockFileUtilTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $root;
    public function setUp()
    {
        $this->root = vfsStream::setup('testDir');

        vfsStream::newFile('test.php')
            ->at($this->root)
            ->setContent('test');
    }

    public function testIsSafeToWriteFileDoesNotExists()
    {
        unlink(vfsStream::url('testDir/test.php'));

        $lockFileUtil = new LockFileUtil(vfsStream::url('testDir'));

        $this->assertTrue(
            $lockFileUtil->isSafeToWrite('test.php'),
            'LockFileUtil::isSafeToWrite should return true if file doesn\'t exist'
        );
    }

    public function testIsSafeToWriteNoLockFile()
    {
        $lockFileUtil = new LockFileUtil(vfsStream::url('testDir'));

        $this->assertFalse(
            $lockFileUtil->isSafeToWrite(vfsStream::url('testDir/test.php')),
            'LockFileUtil::isSafeToWrite should return false if file exists but lock file doesn\'t'
        );
    }

    public function testIsSafeToWriteNotInLockFile()
    {
        vfsStream::newFile('meatup.lock')
            ->at($this->root)
            ->setContent('{}');

        $lockFileUtil = new LockFileUtil(vfsStream::url('testDir'));

        $this->assertFalse(
            $lockFileUtil->isSafeToWrite(vfsStream::url('testDir/test.php')),
            'LockFileUtil::isSafeToWrite should return false if file exists but is not in the lock file'
        );
    }

    public function testIsSafeToWriteLockFileEqual()
    {
        vfsStream::newFile('meatup.lock')
            ->at($this->root)
            ->setContent('{"test.php": "a94a8fe5ccb19ba61c4c0873d391e987982fbbd3"}');

        $lockFileUtil = new LockFileUtil(vfsStream::url('testDir'));

        $this->assertTrue(
            $lockFileUtil->isSafeToWrite(vfsStream::url('testDir/test.php')),
            'LockFileUtil::isSafeToWrite should return true if a file is in the lock file and the sha1 hash matches'
        );
    }

    public function testAddToLockFileShouldCreateLockFile()
    {
        $lockFileUtil = new LockFileUtil(vfsStream::url('testDir'));

        $lockFileUtil->addToLockFile(vfsStream::url('testDir/test.php'));

        $this->assertTrue(
            $this->root->hasChild('meatup.lock'),
            'LockFileUtil::addToLockFile should create the lock file if it does not exist'
        );

        $this->assertEquals(
          '{'.PHP_EOL.'    "test.php": "a94a8fe5ccb19ba61c4c0873d391e987982fbbd3"'.PHP_EOL.'}',
            file_get_contents(vfsStream::url('testDir/meatup.lock')),
            'The content of the created lock file is not correct'
        );
    }

    public function testAddToLockFileShouldReadOverWriteValueInLockFile()
    {
        vfsStream::newFile('meatup.lock')
            ->at($this->root)
            ->setContent('{"test.php": "1234"}');

        $lockFileUtil = new LockFileUtil(vfsStream::url('testDir'));

        $lockFileUtil->addToLockFile(vfsStream::url('testDir/test.php'));

        $this->assertEquals(
            '{'.PHP_EOL.'    "test.php": "a94a8fe5ccb19ba61c4c0873d391e987982fbbd3"'.PHP_EOL.'}',
            file_get_contents(vfsStream::url('testDir/meatup.lock')),
            'The content of the created lock file is not over writen'
        );
    }
}
