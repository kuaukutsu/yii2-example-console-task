# yii2-example-console-task

Example of run a console application on the basis of Yii2.

## installing

```
git clone git@github.com:kuaukutsu/yii2-example-api.git
```

## installing Yii

```
cd ./www
composer global require "fxp/composer-asset-plugin:^1.3.1"
composer install --no-dev
composer run post-create-project-cmd
```

## create task

```php

<?php

namespace app\commands;

/**
 * Class ExampleTaskController
 * @package app\commands
 */
class ExampleTaskController extends AbstractController
{
    /**
     * ежедневно
     */
    public function actionDaily()
    {
        $this->info('run action daily');
    }
}

```

## run example 

```bash

$ ./yii task/daily
2017-09-20 13:30:16 [INFO] task/daily run
2017-09-20 13:30:16 [INFO] run action daily
2017-09-20 13:30:16 [INFO] example-task/daily done
2017-09-20 13:30:16 [INFO] task/daily done

```