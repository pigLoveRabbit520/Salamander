# Salamander
简单快速的PHP框架，基于slim

#  Project Directory Structure

```
app                             [application backend directory]
├── Controller                  [controller directory]
├── Library                     [Library folder]
├── Middleware                  [Middleware folder]
├── Service                     [Logic business folder]
├── settings                    [Slim settings folder]
     |── dependencies.php       [Slim dependencies settings folder]
     |── middleware.php         [Slim middleware settings folder]
├── Tool                        [Tool folder]
├── template                    [template folder]
├── config.php                  [config file]
├── functions.php               [common functions file]
├── ini.php                     [web init file]
├── routes.php                  [routes file]
assets                          [frontend source directory]
public                          [web root directory]
├── index.php                   [entrance php script file]
composer.json                   [composer file]
README.md                       [readme file]