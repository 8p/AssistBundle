Symfony AssistBundle [![Latest Stable Version](https://poser.pugx.org/eightpoints/assist-bundle/v/stable.png)](https://packagist.org/packages/eightpoints/assist-bundle) [![Total Downloads](https://poser.pugx.org/eightpoints/assist-bundle/downloads.png)](https://packagist.org/packages/eightpoints/assist-bundle)
====================
[![knpbundles.com](http://knpbundles.com/8p/AssistBundle/badge)](http://knpbundles.com/8p/AssistBundle)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b10eced0-9f76-4098-b43e-d4b74ef28174/big.png)](https://insight.sensiolabs.com/projects/b10eced0-9f76-4098-b43e-d4b74ef28174)

Small Improvements/Helpers for Symfony.

Requirements
------------
 - PHP 5.3.3 or above (at least 5.3.4 recommended to avoid potential bugs)
 
Installation
------------
Using composer ([Packagist][1]):

``` json
{
    "require": {
        "eightpoints/assist-bundle": "dev-master"
    }
}
```


Usage
-----
Load bundle in AppKernel.php:
``` php
new EightPoints\Bundle\AssistBundle\AssistBundle()
```


Helpers
=======

CommandTimestampOutput
----------------------
To use a timestamp for each printed line on your command just inject the CommandTimestampOutput into your console application.
app/console
``` php
use EightPoints\Bundle\AssistBundle\Console\Output\ConsoleTimestampOutput;

$output = new ConsoleTimestampOutput();
...
$application->run($input, $output);
```

Example output:
``` 
[20:09:30 :: 0.002]     Starting import...
[20:09:31 :: 8.058]     Finished import
```

Authors
-------
 - Florian Preusner ([Twitter][2])

See also the list of [contributors][3] who participated in this project.


License
-------
This bundle is licensed under the MIT License - see the LICENSE file for details


[1]: https://packagist.org/packages/eightpoints/assist-bundle
[2]: http://twitter.com/floeH
[3]: https://github.com/8p/GuzzleBundle/graphs/contributors
