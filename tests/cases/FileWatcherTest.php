<?php
use Stikman\FileWatcher;
use org\bovigo\vfs;

/**
 * Test the watcher class understanding we can not build a
 * vitural filesystem that works with libc glob function. oh well. this will
 * have to do for now.
 *
 */
class WatcherTest extends \PHPUnit_Framework_TestCase
{
    private $dir;

    public function setup()
    {
        $tmp = sys_get_temp_dir();
        $this->dir = $tmp . "/watcher-test";

        if($this->dir === "/") {
            exit();
        }

        if(!is_dir($this->dir)) {
            mkdir($this->dir);
        }

        file_put_contents($this->dir . "/config.ini", "[]");
        file_put_contents($this->dir . "/test.php", "<?php ?>");
        file_put_contents($this->dir . "/my.js", "console.log('hello world'); ");
    }

    public function testFindFiles()
    {
        $watcher = new FileWatcher($this->dir);
        $result = $watcher->findFiles();

        $mock = array(
            $this->dir . "/config.ini",
            $this->dir . "/my.js",
            $this->dir . "/test.php"
        );

        $this->assertEquals($mock, $result);
    }

    public function testCustomPattern()
    {
        $watcher = new FileWatcher($this->dir, "*.php");
        $result = $watcher->findFiles();

        $mock = array(
            $this->dir . "/test.php"
        );

        $this->assertEquals($mock, $result);
    }

    public function testCreateEvent()
    {
        $watcher = new FileWatcher($this->dir);

        // just for simplicity we will set a reference variable to true if the event fires
        $value = false;
        $watcher->on(FileWatcher::CREATE, function() use (&$value) {
            $value = true;
        });

        // set the watcher to stop as soon as it starts
        $watcher->setIterations(1);
        $watcher->start();

        // now change the file system to add a file
        file_put_contents($this->dir . "/newfile.txt", "content");
        $watcher->start();

        $this->assertTrue($value);
    }

    public function testDeleteEvent()
    {
        $watcher = new FileWatcher($this->dir);

        // just for simplicity we will set a reference variable to true if the event fires
        $value = false;
        $watcher->on(FileWatcher::DELETE, function() use (&$value) {
                $value = true;
            });

        // set the watcher to stop as soon as it starts
        $watcher->setIterations(1);
        $watcher->start();

        // now change the file system to add a file
        unlink($this->dir . "/config.ini");
        $watcher->start();

        $this->assertTrue($value);
    }

    public function testModifiedEvent()
    {
        $watcher = new FileWatcher($this->dir);

        // just for simplicity we will set a reference variable to true if the event fires
        $value = false;
        $watcher->on(FileWatcher::MODIFIED, function() use (&$value) {
                $value = true;
            });

        // set the watcher to stop as soon as it starts
        $watcher->setIterations(1);
        $watcher->start();

        // now change the file system to add a file
        file_put_contents($this->dir . "/config.ini", "[this is new]");
        $watcher->start();

        $this->assertTrue($value);
    }

    public function teardown()
    {
        $tmp = sys_get_temp_dir();
        $files = glob($tmp . "/watcher-test/*");
        foreach ($files as $file) {
            @unlink($file);
        }

        rmdir($tmp . "/watcher-test");
    }

}
 