# Salamander
简单快速的PHP框架，基于slim

# 使用
1.配置web根目录（nginx举例），请求重写到index.php文件
```
root D:/your_web_project/Salamander/public;
index  index.php index.html index.htm;

location / {
    try_files $uri $uri/ /index.php$is_args$args;
}

# pass the PHP scripts to FastCGI server listening on 127.0.0.1:9000
location ~ \.php$ {
    fastcgi_pass   127.0.0.1:9000;
    fastcgi_index  index.php;
    fastcgi_param  SCRIPT_FILENAME    $document_root$fastcgi_script_name;
    include        fastcgi_params;
}
```
2.安装PHP依赖
```
compose install
```


# 框架目录结构

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
```

# 说明
## 整体介绍
路由分发和依赖注入用了[**slim**](https://github.com/slimphp/Slim)，routes.php设置请求路由，settings/dependencies.php设置全局依赖

## Controller
**控制层**：写一个类继承BaseController类，类方法会被注入三个参数
* Request $request
* Response $response
* Array $args

## Service
**业务逻辑层**：写一个类继承BaseService类，然后可以操作db（通过db属性），调用其它依赖实现业务逻辑 