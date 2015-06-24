# Watcher  [![Build Status](https://secure.travis-ci.org/stikmanw/Watcher.png)](http://travis-ci.org/stikmanw/Watcher)
File system watcher allowing for observeable handlers when a directory changes or content of the directory changes.

Requirements
------------
* PHP 5.4 >= 
* Linux Files System / Mac OSX tested 
* Windows (untested)

Description
-----------
You used the file FileWatcher like you would use typical event listener. The main advantage to this class is it does not require installing any additional libraries and can simply be installed via composer and used immidiately. You can attach multiple listeners to the 3 main events: 

* __CREATE__ - File has been created
* __MODIFIED__ - File has been changed (currently uses a md5 hash to identify change)
* __DELETE__ - File has been deleted since last check

Example
-------
Attach to create events 
```php
use Stikman\FileWatcher; 

$watcher = new FileWatcher("/tmp/mystuff"); 
$watcher->on(FileWatcher::CREATE, function() {
  // do stuff 
}); 
```

Set the pulling interval
```php
$watcher = new FileWatcher("/tmp/mystuff"); 
$watcher->setInterval(100); // 100 millioseconds to check disk defaults to 25ms
```

Custom pattern match against file system for monitoring configuration data
```php
$watcher = new FileWatcher("/tmp/mystuff", "*.json"); 
```

License
-------
MIT 

