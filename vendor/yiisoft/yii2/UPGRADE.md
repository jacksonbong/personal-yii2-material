Upgrading Instructions for Yii Framework 2.0
============================================

This file contains the upgrade notes for Yii 2.0. These notes highlight changes that
could break your application when you upgrade Yii from one version to another.
Even though we try to ensure backwards compabitilty (BC) as much as possible, sometimes
it is not possible or very complicated to avoid it and still create a good solution to
a problem. You may also want to check out the [versioning policy](https://github.com/yiisoft/yii2/blob/master/docs/internals/versions.md)
for further details.

Upgrading in general is as simple as updating your dependency in your composer.json and
running `composer update`. In a big application however there may be more things to consider,
which are explained in the following.

> Note: This document assumes you have composer [installed globally](http://www.yiiframework.com/doc-2.0/guide-start-installation.html#installing-composer)
so that you can run the `composer` command. If you have a `composer.phar` file inside of your project you need to
replace `composer` with `php composer.phar` in the following.

> Tip: Upgrading dependencies of a complex software project always comes at the risk of breaking something, so make sure
you have a backup (you should be doing this anyway ;) ).

In case you use [composer asset plugin](https://github.com/fxpio/composer-asset-plugin) instead of the currently recommended
[asset-packagist.org](https://asset-packagist.org) to install Bower and NPM assets, make sure it is upgraded to the latest version as well. To ensure best stability you should also upgrade composer in this step:

    composer self-update
    composer global require "fxp/composer-asset-plugin:^1.4.1" --no-plugins

The simple way to upgrade Yii, for example to version 2.0.10 (replace this with the version you want) will be running `composer require`:

    composer require "yiisoft/yii2:~2.0.10" --update-with-dependencies

This command will only upgrade Yii and its direct dependencies, if necessary. Without `--update-with-dependencies` the
upgrade might fail when the Yii version you chose has slightly different dependencies than the version you had before.
`composer require` will by default not update any other packages as a safety feature.

Another way to upgrade is to change the `composer.json` file to require the new Yii version and then
run `composer update` by specifying all packages that are allowed to be updated.

    composer update yiisoft/yii2 yiisoft/yii2-composer bower-asset/inputmask

The above command will only update the specified packages and leave the versions of all other dependencies intact.
This helps to update packages step by step without causing a lot of package version changes that might break in some way.
If you feel lucky you can of course update everything to the latest version by running `composer update` without
any restrictions.

After upgrading you should check whether your application still works as expected and no tests are broken.
See the following notes on which changes to consider when upgrading from one version to another.

> Note: The following upgrading instructions are cumulative. That is,
if you want to upgrade from version A to version C and there is
version B between A and C, you need to follow the instructions
for both A and B.

Upgrade from Yii 2.0.x
----------------------

* PHP requirements were raised to 7.1. Make sure your code is updated accordingly.
* memcache PECL extension support was dropped. Use memcached PECL extension instead.
* Following new methods have been added to `yii\mail\MessageInterface` `addHeader()`, `setHeader()`, `getHeader()`, `setHeaders()`
  providing ability to setup custom mail headers. Make sure your provide implementation for those methods, while
  creating your own mailer solution.
* `::className()` method calls should be replaced with [native](http://php.net/manual/en/language.oop5.basic.php#language.oop5.basic.class.class) `::class`.
  When upgrading to Yii 3.0, You should do a global search and replace for `::className()` to `::class`.
  All calls on objects via `->className()` should be replaced by a call to `get_class()`.
* Dependency injection (DI) layer has been replaced by "yiisoft/di" package. Make sure to update class/object definitions at
  your code to match the syntax used by it. In particular: you should use '__class' array key instead of 'class' for
  class name specification.
* XCache and Zend data cache support was removed. Switch to another caching backends.
* Rename `InvalidParamException` usage to `InvalidArgumentException`.
* CAPTCHA package has been moved into separate extension https://github.com/yiisoft/yii2-captcha.
  Include it in your composer.json if you use it.
* JQuery related code (e.g. `yii.js`, `yiiActiveForm.js`, `yiiGridView.js`) has been moved into separate extension https://github.com/yiisoft/yii2-jquery.
  Include it in your composer.json if you use it.
* REST API package has been moved into separate extension https://github.com/yiisoft/yii2-rest.
  Include it in your composer.json if you use it.
* MSSQL Server DB package has been moved into separate extension https://github.com/yiisoft/yii2-mssql.
  Include it in your composer.json if you use it.
* Oracle DB package has been moved into separate extension https://github.com/yiisoft/yii2-oracle.
  Include it in your composer.json if you use it.
* CUBRID support has been removed, package `yii\db\cubrid\*` is no longer available.
  If you need to use CUBRID further you should create your own integration for it.
* Masked input field widget was moved into separate extension https://github.com/yiisoft/yii2-maskedinput.
  Include it in your composer.json if you use it.
* PJAX support has been removed: widget `yii\widget\Pjax`, method `yii\web\Request::getIsPjax()`, PJAX related checks and
  headers are no longer available. If you wish to use PJAX further you should create your own integration for it.
* If you've used ApcCache and set `useApcu` in your config, remove the option.
* During mail view rendering the `$message` variable is no longer set by default to be an instance of `yii\mail\MessageInterface`. Instead it is available via `$this->context->message` expression.
* `yii\mail\BaseMailer::render()` method has been removed. Make sure you do not use it anywhere in your program.
  Mail view rendering is now encapsulated into `yii\mail\Template` class.
* Properties `view`, `viewPath`, `htmlLayout` and `textLayout` have been moved from `yii\mail\BaseMailer` to `yii\mail\Composer` class,
  which now encapsulates message composition.
* Interface of `yii\log\Logger` has been changed according to PSR-3 `Psr\Log\LoggerInterface`.
  Make sure you update your code accordingly in case you invoke `Logger` methods directly.
* Constants `yii\log\Logger::LEVEL_ERROR`, `yii\log\Logger::LEVEL_WARNING` and so on have been removed.
  Use constants from `Psr\Log\LogLevel` instead.
* Method `yii\BaseYii::trace()` has been renamed to `debug()`. Make sure you use correct name for it.
* Class `yii\log\Dispatcher` has been removed as well as application 'log' component. Log targets
  now should be configured using `yii\base\Application::$logger` property. Neither 'log' or 'logger'
  components should be present at `yii\base\Application::$bootstrap`
* Profiling related functionality has been extracted into a separated component under `yii\profile\ProfilerInterface`.
  Profiling messages should be collection using `yii\base\Application::$profiler`. In case you wish to
  continue storing profiling messages along with the log ones, you may use `yii\profile\LogTarget` profiling target.
* Classes `yii\web\Request` and `yii\web\Response` have been updated to match interfaces `Psr\Http\Message\ServerRequestInterface`
  and `Psr\Http\Message\ResponseInterface` accordingly. Make sure you use their methods and properties correctly.
  In particular: method `getHeaders()` and corresponding virtual property `$headers` are no longer return `HeaderCollection`
  instance, you can use `getHeaderCollection()` in order to use old headers setup syntax; `Request|Response::$version` renamed
  to `Request|Response::$protocolVersion`; `Response::$statusText` renamed `Response::$reasonPhrase`;
  `Request::$bodyParams` renamed to `Request::$parsedBody`; `Request::getBodyParam()` renamed to `Request::getParsedBodyParam()`;
* `yii\web\Response::$stream` is no longer available, use `yii\web\Response::withBody()` to setup stream response.
  You can use `Response::$bodyRange` to setup stream content range.
* Classes `yii\web\CookieCollection`, `yii\web\HeaderCollection` and `yii\web\UploadedFile` have been moved under
  namespace `yii\http\*`. Make sure to refer to those classes using correct fully qualified name.
* Public interface of `UploadedFile` class has been changed according to `Psr\Http\Message\UploadedFileInterface`.
  Make sure you refer to its properties and methods with correct names.
* `yii\captcha\CaptchaAction` has been refactored. Rendering logic was extracted into `yii\captcha\DriverInterface`, which
  instance is available via `yii\captcha\CaptchaAction::$driver` field. All image settings now should be passed to
  the driver fields instead of action. Automatic detection of the rendering driver is no longer supported.
* `yii\captcha\Captcha::checkRequirements()` method has been removed.
* All cache related classes interface has been changed according to PSR-16 "Simple Cache" specification. Make sure you
  change your invocations for the cache methods accordingly. The most notable changes affects methods `get()` and `getMultiple()`
  as they now accept `$default` argument, which value will be returned in case there is no value in the cache. This makes
  the default return value to be `null` instead of `false`.
* Particular cache implementation should now be configured as `yii\caching\Cache::$handler` property instead of the
  component itself. Properties `$defaultTtl`, `$serializer` and `$keyPrefix` has been moved to cache handler and should
  be configured there. Creating your own cache implementation you should implement `\Psr\SimpleCache\CacheInterface` or
  extend `yii\caching\SimpleCache` abstract class. Use `yii\caching\CacheInterface` only if you wish to replace `yii\caching\Cache`
  component providing your own solution for cache dependency handling.
* `yii\caching\SimpleCache::$serializer` now should be `yii\serialize\SerializerInterface` instance or its DI compatible configuration.
  Thus it does no longer accept pair of serialize/unserialize functions as an array. Use `yii\serialize\CallbackSerializer` or
  other predefined serializer class from `yii\serialize\*` namespace instead.
* Console command used to clear cache now calls related actions "clear" instead of "flush".
* Yii autoloader was removed in favor of Composer-generated one. You should remove explicit inclusion of `Yii.php` from
  your entry `index.php` scripts. In case you have relied on class map, use `composer.json` instead of configuring it
  with PHP. For details please refer to [guide on autoloading](https://github.com/yiisoft/yii2/blob/3.0/docs/guide/concept-autoloading.md),
  [guide on customizing helpers](https://github.com/yiisoft/yii2/blob/3.0/docs/guide/helper-overview.md#customizing-helper-classes-)
  and [guide on Working with Third-Party Code](https://github.com/yiisoft/yii2/blob/3.0/docs/guide/tutorial-yii-integration.md).
* The signature of `yii\web\RequestParserInterface::parse()` was changed. The method now accepts the `yii\web\Request` instance
  as a sole argument. Make sure you declare and implement this method correctly, while creating your own request parser.
* Uploaded file retrieve methods have been moved from `yii\http\UploadedFile` to `yii\web\Request`. You should use `Request::getUploadedFileByName()`
  instead of `UploadedFile::getInstanceByName()` and `Request::getUploadedFilesByName()` instead of `UploadedFile::getInstancesByName()`.
  Instead of `UploadedFile::getInstance()` and `UploadedFile::getInstances()` use construction `$model->load(Yii::$app->request->getUploadedFiles())`.
* Result of `yii\web\Request::getBodyParams()` now includes uploaded files (e.g. result of `yii\web\Request::getUploadedFiles()`).
  You should aware that instances of `yii\http\UploadedFile` may appear inside body params.
* The following method signature have changed. If you override any of them in your code, you have to adjust these places:
  `yii\db\QueryBuilder::buildGroupBy($columns)` -> `buildGroupBy($columns, &$params)`
  `yii\db\QueryBuilder::buildOrderByAndLimit($sql, $orderBy, $limit, $offset)` -> `buildOrderByAndLimit($sql, $orderBy, $limit, $offset, &$params)`
  `yii\widgets\ActiveField::hint($content = null, $options = [])`
  `yii\base\View::renderDynamic($statements)` -> `yii\base\View::renderDynamic($statements, array $params = [])`
* `yii\filters\AccessControl` has been optimized by only instantiating rules at the moment of use.
   This could lead to a potential BC-break if you are depending on $rules to be instantiated in init().
* `yii\widgets\BaseListView::run()` and `yii\widgets\GridView::run()` now return content, instead of echoing it.
  Normally we call `BaseListView::widget()` and for this case behavior is NOT changed.
  In case you call `::run()` method, ensure that its return is processed correctly.
* `yii\web\UrlNormalizer` is now enabled by default in `yii\web\UrlManager`.
  If you are using `yii\web\Request::resolve()` or `yii\web\UrlManager::parseRequest()` directly, make sure that
  all potential exceptions are handled correctly or set `yii\web\UrlNormalizer::$normalizer` to `false` to disable normalizer.
* `yii\base\InvalidParamException` was renamed to `yii\base\InvalidArgumentException`.
* Classes `yii\widgets\ActiveForm`, `yii\widgets\ActiveField`, `yii\grid\GridView`, `yii\web\View` have been refactored
  to be more generic without including any 'JQuery' support and client-side processing (validation, automatic submit etc.).
  You should use widget behaviors from `yii\jquery\*` package to make old code function as before. E.g. attach `yii\jquery\ActiveFormClientScript`
  to `yii\widgets\ActiveForm`, `yii\jquery\GridViewClientScript` to `yii\grid\GridView` and so on.
* Fields `$enableClientScript` and `$attributes` have been removed from `yii\widgets\ActiveForm`. Make sure
  you do not use them or specify them during `ActiveForm::begin()` invocation.
* Field `yii\grid\GridView::$filterSelector` has been removed. Make sure you do not use it or specify it during
  `GridView::widget()` invocation. Use `yii\jquery\GridViewClientScript::$filterSelector` instead.
* Method `getClientOptions()` has been removed from `yii\validators\Validator` and all its descendants.
  All implementations of `clientValidateAttribute()` around built-in validators now return `null`.
  Use classes from `yii\jquery\validators\client\*` for building client validation (JavaScript) code.
* Assets `yii\web\JqueryAsset`, `yii\web\YiiAsset`, `yii\validators\ValidationAsset` have been moved under `yii\jquery\*`
  namespace. Make sure you refer to the new full-qualified names of this classes.
* Methods `yii\validators\Validator::formatMessage()`, `yii\validators\IpValidator::getIpParsePattern()` and
  `yii\validators\FileValidator::buildMimeTypeRegexp()` have been made `public`. Make sure you use correct
  access level specification in case you override these methods.
* Default script position for the `yii\web\View::registerJs()` changed to `View::POS_END`.
* Package "ezyang/htmlpurifier" has been made optional and is not installed by default. If you need to use
  `yii\helpers\HtmlPurifier` or `yii\i18n\Formatter::asHtml()` (e.g. 'html' data format), you'll have to install
  this package manually for your project.
* `yii\BaseYii::powered()` method has been removed. Please add "Powered by Yii" link either right into HTML or using
  `yii\helpers\Html::a()`.
* `yii\i18n\MessageFormatter` no longer supports parameter names with `.`, `-`, `=` and other symbols that are used in
  pattern syntax following directly how it works in intl/ICU. If you use such parameters names, replace special
  symbols with `_`.
* `yii\i18n\MessageFormatter::parse()` method was removed. If you have a rare case where it's used copy-paste it from
  2.0 branch to your project. 


Upgrade from Yii 2.0.15
-----------------------

* Updated dependency to `cebe/markdown` to version `1.2.x`.
  If you need stick with 1.1.x, you can specify that in your `composer.json` by
  adding the following line in the `require` section:

  ```json
  "cebe/markdown": "~1.1.0",
  ```


Upgrade from Yii 2.0.14
-----------------------

* When hash format condition (array) is used in `yii\db\ActiveRecord::findOne()` and `findAll()`, the array keys (column names)
  are now limited to the table column names. This is to prevent SQL injection if input was not filtered properly.
  You should check all usages of `findOne()` and `findAll()` to ensure that input is filtered correctly.
  If you need to find models using different keys than the table columns, use `find()->where(...)` instead.

  It's not an issue in the default generated code though as ID is filtered by
  controller code:

  The following code examples are **not** affected by this issue (examples shown for `findOne()` are valid also for `findAll()`):

  ```php
  // yii\web\Controller ensures that $id is scalar
  public function actionView($id)
  {
      $model = Post::findOne($id);
      // ...
  }
  ```

  ```php
  // casting to (int) or (string) ensures no array can be injected (an exception will be thrown so this is not a good practise)
  $model = Post::findOne((int) Yii::$app->request->get('id'));
  ```

  ```php
  // explicitly specifying the colum to search, passing a scalar or array here will always result in finding a single record
  $model = Post::findOne(['id' => Yii::$app->request->get('id')]);
  ```

  The following code however **is vulnerable**, an attacker could inject an array with an arbitrary condition and even exploit SQL injection:

  ```php
  $model = Post::findOne(Yii::$app->request->get('id'));
  ```

  For the above example, the SQL injection part is fixed with the patches provided in this release, but an attacker may still be able to search
  records by different condition than a primary key search and violate your application business logic. So passing user input directly like this can cause problems and should be avoided.


Upgrade from Yii 2.0.13
-----------------------

* Constants `IPV6_ADDRESS_LENGTH`, `IPV4_ADDRESS_LENGTH` were moved from `yii\validators\IpValidator` to `yii\helpers\IpHelper`.
  If your application relies on these constants, make sure to update your code to follow the changes.

* `yii\base\Security::compareString()` is now throwing `yii\base\InvalidArgumentException` in case non-strings are compared.

* `yii\db\ExpressionInterface` has been introduced to represent a wider range of SQL expressions. In case you check for
  `instanceof yii\db\Expression` in your code, you might consider changing that to checking for the interface and use the newly
  introduced methods to retrieve the expression content.

* Added JSON support for PostgreSQL and MySQL as well as Arrays support for PostgreSQL in ActiveRecord layer.
  In case you already implemented such support yourself, please switch to Yii implementation.
  * For MySQL JSON and PgSQL JSON & JSONB columns Active Record will return decoded JSON (that can be either array or scalar) after data population
  and expects arrays or scalars to be assigned for further saving them into a database.
  * For PgSQL Array columns Active Record will return `yii\db\ArrayExpression` object that acts as an array
  (it implements `ArrayAccess`, `Traversable` and `Countable` interfaces) and expects array or `yii\db\ArrayExpression` to be
  assigned for further saving it into the database.

  In case this change makes the upgrade process to Yii 2.0.14 too hard in your project, you can [switch off the described behavior](https://github.com/yiisoft/yii2/issues/15716#issuecomment-368143206)
  Then you can take your time to change your code and then re-enable arrays or JSON support.

* `yii\db\PdoValue` class has been introduced to replace a special syntax that was used to declare PDO parameter type 
  when binding parameters to an SQL command, for example: `['value', \PDO::PARAM_STR]`.
  You should use `new PdoValue('value', \PDO::PARAM_STR)` instead. Old syntax will be removed in Yii 3.0.

* `yii\db\QueryBuilder::conditionBuilders` property and method-based condition builders are no longer used. 
  Class-based conditions and builders are introduced instead to provide more flexibility, extensibility and
  space to customization. In case you rely on that property or override any of default condition builders, follow the 
  special [guide article](http://www.yiiframework.com/doc-2.0/guide-db-query-builder.html#adding-custom-conditions-and-expressions)
  to update your code.

* Protected method `yii\db\ActiveQueryTrait::createModels()` does not apply indexes as defined in `indexBy` property anymore.  
  In case you override default ActiveQuery implementation and relied on that behavior, call `yii\db\Query::populate()`
  method instead to index query results according to the `indexBy` parameter.

* Log targets (like `yii\log\EmailTarget`) are now throwing `yii\log\LogRuntimeException` in case log can not be properly exported.

* You can start preparing your application for Yii 3.0 by doing the following:

  - Replace `::className()` calls with `::class` (if you’re running PHP 5.5+).
  - Replace usages of `yii\base\InvalidParamException` with `yii\base\InvalidArgumentException`.
  - Replace calls to `Yii::trace()` with `Yii::debug()`.
  - Remove calls to `yii\BaseYii::powered()`.
  - If you are using XCache or Zend data cache, those are going away in 3.0 so you might want to start looking for an alternative.

* In case you aren't using CSRF cookies (REST APIs etc.) you should turn them off explicitly by setting
  `\yii\web\Request::$enableCsrfCookie` to `false` in your config file. 

Upgrade from Yii 2.0.12
-----------------------

* The `yii\web\Request` class allowed to determine the value of `getIsSecureConnection()` form the
  `X-Forwarded-Proto` header if the connection was made via a normal HTTP request. This behavior
  was insecure as the header could have been set by a malicious client on a non-HTTPS connection.
  With 2.0.13 Yii adds support for configuring trusted proxies. If your application runs behind a reverse proxy and relies on
  `getIsSecureConnection()` to return the value form the `X-Forwarded-Proto` header you need to explicitly allow
  this in the Request configuration. See the [guide](http://www.yiiframework.com/doc-2.0/guide-runtime-requests.html#trusted-proxies) for more information.

  This setting also affects you when Yii is running on IIS webserver, which sets the `X-Rewrite-Url` header.
  This header is now filtered by default and must be listed in trusted hosts to be detected by Yii:

  ```php
  [   // accept X-Rewrite-Url from all hosts, as it will be set by IIS
      '/.*/' => ['X-Rewrite-Url'],
  ]
  ```

* For compatibiliy with [PHP 7.2 which does not allow classes to be named `Object` anymore](https://wiki.php.net/rfc/object-typehint),
  we needed to rename `yii\base\Object` to `yii\base\BaseObject`.
  
  `yii\base\Object` still exists for backwards compatibility and will be loaded if needed in projects that are
  running on PHP <7.2. The compatibility class `yii\base\Object` extends from `yii\base\BaseObject` so if you
  have classes that extend from `yii\base\Object` these would still work.
  
  What does not work however will be code that relies on `instanceof` checks or `is_subclass_of()` calls
  for `yii\base\Object` on framework classes as these do not extend `yii\base\Object` anymore but only
  extend from `yii\base\BaseObject`. In general such a check is not needed as there is a `yii\base\Configurable`
  interface you should check against instead.
  
  Here is a visualisation of the change (`a < b` means "b extends a"):
  
  ```
  Before:
  
  yii\base\Object < Framework Classes
  yii\base\Object < Application Classes
  
  After Upgrade:
  
  yii\base\BaseObject < Framework Classes
  yii\base\BaseObject < yii\base\Object < Application Classes

  ```
  
  If you want to upgrade PHP to version 7.2 in your project you need to remove all cases that extend `yii\base\Object`
  and extend from `yii\base\BaseObject` instead:
  
  ```
  yii\base\BaseObject < Framework Classes
  yii\base\BaseObject < Application Classes
  ```
  
  For extensions that have classes extending from `yii\base\Object`, to be compatible with PHP 7.2, you need to
  require `"yiisoft/yii2": "~2.0.13"` in composer.json and change affected classes to extend from `yii\base\BaseObject`
  instead. It is not possible to allow Yii versions `<2.0.13` and be compatible with PHP 7.2 or higher.

* A new method `public static function instance($refresh = false);` has been added to the `yii\db\ActiveRecordInterface` via a new
  `yii\base\StaticInstanceInterface`. This change may affect your application in the following ways:

  - If you have an `instance()` method defined in an `ActiveRecord` or `Model` class, you need to check whether the behavior is
    compatible with the method added by Yii.
  - Otherwise this method is implemented in the `yii\base\Model`, so the change only affects your code if you implement `ActiveRecordInterface`
    in a class that does not extend `Model`. You may use `yii\base\StaticInstanceTrait` to implement it.

* Fixed built-in validator creating when model has a method with the same name.

  It is documented, that for the validation rules declared in model by `yii\base\Model::rules()`, validator can be either
  a built-in validator name, a method name of the model class, an anonymous function, or a validator class name.
  Before this change behavior was inconsistent with the documentation: method in the model had higher priority, than
  a built-in validator. In case you have relied on this behavior, make sure to fix it.

* Behavior was changed for methods `yii\base\Module::get()` and `yii\base\Module::has()` so in case when the requested
  component was not found in the current module, the parent ones will be checked for this component hierarchically.
  Considering that the root parent module is usually an application, this change can reduce calls to global `Yii::$app->get()`,
  and replace them with module-scope calls to `get()`, making code more reliable and easier to test.
  However, this change may affect your application if you have code that uses method `yii\base\Module::has()` in order
  to check existence of the component exactly in this specific module. In this case make sure the logic is not corrupted.

* If you are using "asset" command to compress assets and your web application `assetManager` has `linkAssets` turned on,
  make sure that "asset" command config has `linkAssets` turned on as well.


Upgrade from Yii 2.0.11
-----------------------

* `yii\i18n\Formatter::normalizeDatetimeValue()` returns now array with additional third boolean element
  indicating whether the timestamp has date information or it is just time value.

* `yii\grid\DataColumn` filter is now automatically generated as dropdown list with localized `Yes` and `No` strings
  in case of `format` being set to `boolean`.
 
* The signature of `yii\db\QueryBuilder::prepareInsertSelectSubQuery()` was changed. The method has got an extra optional parameter
  `$params`.

* The signature of `yii\cache\Cache::getOrSet()` has been adjusted to also accept a callable and not only `Closure`.
  If you extend this method, make sure to adjust your code.
  
* `yii\web\UrlManager` now checks if rules implement `getCreateUrlStatus()` method in order to decide whether to use
  internal cache for `createUrl()` calls. Ensure that all your custom rules implement this method in order to fully 
  benefit from the acceleration provided by this cache.

* `yii\filters\AccessControl` now can be used without `user` component. This has two consequences:

  1. If used without user component, `yii\filters\AccessControl::denyAccess()` throws `yii\web\ForbiddenHttpException` instead of redirecting to login page.
  2. If used without user component, using `AccessRule` matching a role throws `yii\base\InvalidConfigException`.
  
* Inputmask package name was changed from `jquery.inputmask` to `inputmask`. If you've configured path to
  assets manually, please adjust it. 

Upgrade from Yii 2.0.10
-----------------------

* A new method `public function emulateExecution($value = true);` has been added to the `yii\db\QueryInterace`.
  This method is implemented in the `yii\db\QueryTrait`, so this only affects your code if you implement QueryInterface
  in a class that does not use the trait.

* `yii\validators\FileValidator::getClientOptions()` and `yii\validators\ImageValidator::getClientOptions()` are now public.
  If you extend from these classes and override these methods, you must make them public as well.

* `yii\widgets\MaskedInput` inputmask dependency was updated to `~3.3.3`.
  [See its changelog for details](https://github.com/RobinHerbots/Inputmask/blob/3.x/CHANGELOG.md).

* PJAX: Auto generated IDs of the Pjax widget have been changed to use their own prefix to avoid conflicts.
  Auto generated IDs are now prefixed with `p` instead of `w`. This is defined by the `$autoIdPrefix`
  property of `yii\widgets\Pjax`. If you have any PHP or Javascript code that depends on autogenerated IDs
  you should update these to match this new value. It is not a good idea to rely on auto generated values anyway, so
  you better fix these cases by specifying an explicit ID.


Upgrade from Yii 2.0.9
----------------------

* RBAC: `getChildRoles()` method was added to `\yii\rbac\ManagerInterface`. If you've implemented your own RBAC manager
  you need to implement new method.

* Microsoft SQL `NTEXT` data type [was marked as deprecated](https://msdn.microsoft.com/en-us/library/ms187993.aspx) in MSSQL so
  `\yii\db\mssql\Schema::TYPE_TEXT` was changed from `'ntext'` to `'nvarchar(max)'

* Method `yii\web\Request::getBodyParams()` has been changed to pass full value of 'content-type' header to the second
  argument of `yii\web\RequestParserInterface::parse()`. If you create your own custom parser, which relies on `$contentType`
  argument, ensure to process it correctly as it may content additional data.

* `yii\rest\Serializer` has been changed to return a JSON array for collection data in all cases to be consistent among pages
  for data that is not indexed starting by 0. If your API relies on the Serializer to return data as JSON objects indexed by
  PHP array keys, you should set `yii\rest\Serializer::$preserveKeys` to `true`.


Upgrade from Yii 2.0.8
----------------------

* Part of code from `yii\web\User::loginByCookie()` method was moved to new `getIdentityAndDurationFromCookie()`
  and `removeIdentityCookie()` methods. If you override `loginByCookie()` method, update it in order use new methods.

* Fixture console command syntax was changed from `yii fixture "*" -User` to `yii fixture "*, -User"`. Upgrade your
  scripts if necessary.

Upgrade from Yii 2.0.7
----------------------

* The signature of `yii\helpers\BaseArrayHelper::index()` was changed. The method has got an extra optional parameter
  `$groups`.

* `yii\helpers\BaseArrayHelper` methods `isIn()` and `isSubset()` throw `\yii\base\InvalidParamException`
  instead of `\InvalidArgumentException`. If you wrap calls of these methods in try/catch block, change expected
  exception class.

* `yii\rbac\ManagerInterface::canAddChild()` method was added. If you have custom backend for RBAC you need to implement
  it.

* The signature of `yii\web\User::loginRequired()` was changed. The method has got an extra optional parameter
  `$checkAcceptHeader`.

* The signature of `yii\db\ColumnSchemaBuilder::__construct()` was changed. The method has got an extra optional
  parameter `$db`. In case you are instantiating this class yourself and using the `$config` parameter, you will need to
  move it to the right by one.

* String types in the MSSQL column schema map were upgraded to Unicode storage types. This will have no effect on
  existing columns, but any new columns you generate via the migrations engine will now store data as Unicode.

* Output buffering was introduced in the pair of `yii\widgets\ActiveForm::init()` and `::run()`. If you override any of
  these methods, make sure that output buffer handling is not corrupted. If you call the parent implementation, when
  overriding, everything should work fine. You should be doing that anyway.

Upgrade from Yii 2.0.6
----------------------

* Added new requirement: ICU Data version >= 49.1. Please, ensure that your environment has ICU data installed and
  up to date to prevent unexpected behavior or crashes. This may not be the case on older systems e.g. running Debian Wheezy.

  > Tip: Use Yii 2 Requirements checker for easy and fast check. Look for `requirements.php` in root of Basic and Advanced
  templates (howto-comment is in head of the script).

* The signature of `yii\helpers\BaseInflector::transliterate()` was changed. The method is now public and has an
  extra optional parameter `$transliterator`.

* In `yii\web\UrlRule` the `pattern` matching group names are being replaced with the placeholders on class
  initialization to support wider range of allowed characters. Because of this change:

  - You are required to flush your application cache to remove outdated `UrlRule` serialized objects.
    See the [Cache Flushing Guide](http://www.yiiframework.com/doc-2.0/guide-caching-data.html#cache-flushing)
  - If you implement `parseRequest()` or `createUrl()` and rely on parameter names, call `substitutePlaceholderNames()`
    in order to replace temporary IDs with parameter names after doing matching.

* The context of `yii.confirm` JavaScript function was changed from `yii` object to the DOM element which triggered
  the event.

  - If you overrode the `yii.confirm` function and accessed the `yii` object through `this`, you must access it
    with global variable `yii` instead.

* Traversable objects are now formatted as arrays in XML response to support SPL objects and Generators. Previous
  behavior could be turned on by setting `XmlResponseFormatter::$useTraversableAsArray` to `false`.

* If you've implemented `yii\rbac\ManagerInterface` you need to implement additional method `getUserIdsByRole($roleName)`.

* If you're using ApcCache with APCu, set `useApcu` to `true` in the component config.

* The `yii\behaviors\SluggableBehavior` class has been refactored to make it more reusable.
  Added new `protected` methods:

  - `isSlugNeeded()`
  - `makeUnique()`

  The visibility of the following Methods has changed from `private` to `protected`:

  - `validateSlug()`
  - `generateUniqueSlug()`

* The `yii\console\controllers\MessageController` class has been refactored to be better configurable and now also allows
  setting a lot of configuration options via command line. If you extend from this class, make sure it works as expected after
  these changes.

Upgrade from Yii 2.0.5
----------------------

* The signature of the following methods in `yii\console\controllers\MessageController` has changed. They have an extra parameter `$markUnused`.
  - `saveMessagesToDb($messages, $db, $sourceMessageTable, $messageTable, $removeUnused, $languages, $markUnused)`
  - `saveMessagesToPHP($messages, $dirName, $overwrite, $removeUnused, $sort, $markUnused)`
  - `saveMessagesCategoryToPHP($messages, $fileName, $overwrite, $removeUnused, $sort, $category, $markUnused)`
  - `saveMessagesToPO($messages, $dirName, $overwrite, $removeUnused, $sort, $catalog, $markUnused)`

Upgrade from Yii 2.0.4
----------------------

Upgrading from 2.0.4 to 2.0.5 does not require any changes.

Upgrade from Yii 2.0.3
----------------------

* Updated dependency to `cebe/markdown` to version `1.1.x`.
  If you need stick with 1.0.x, you can specify that in your `composer.json` by
  adding the following line in the `require` section:

  ```json
  "cebe/markdown": "~1.0.0",
  ```

Upgrade from Yii 2.0.2
----------------------

Starting from version 2.0.3 Yii `Security` component relies on OpenSSL crypto lib instead of Mcrypt. The reason is that
Mcrypt is abandoned and isn't maintained for years. Therefore your PHP should be compiled with OpenSSL support. Most
probably there's nothing to worry because it is quite typical.

If you've extended `yii\base\Security` to override any of the config constants you have to update your code:

    - `MCRYPT_CIPHER` — now encoded in `$cipher` (and hence `$allowedCiphers`).
    - `MCRYPT_MODE` — now encoded in `$cipher` (and hence `$allowedCiphers`).
    - `KEY_SIZE` — now encoded in `$cipher` (and hence `$allowedCiphers`).
    - `KDF_HASH` — now `$kdfHash`.
    - `MAC_HASH` — now `$macHash`.
    - `AUTH_KEY_INFO` — now `$authKeyInfo`.

Upgrade from Yii 2.0.0
----------------------

* Upgraded Twitter Bootstrap to [version 3.3.x](http://blog.getbootstrap.com/2014/10/29/bootstrap-3-3-0-released/).
  If you need to use an older version (i.e. stick with 3.2.x) you can specify that in your `composer.json` by
  adding the following line in the `require` section:

  ```json
  "bower-asset/bootstrap": "3.2.*",
  ```

Upgrade from Yii 2.0 RC
-----------------------

* If you've implemented `yii\rbac\ManagerInterface` you need to add implementation for new method `removeChildren()`.

* The input dates for datetime formatting are now assumed to be in UTC unless a timezone is explicitly given.
  Before, the timezone assumed for input dates was the default timezone set by PHP which is the same as `Yii::$app->timeZone`.
  This causes trouble because the formatter uses `Yii::$app->timeZone` as the default values for output so no timezone conversion
  was possible. If your timestamps are stored in the database without a timezone identifier you have to ensure they are in UTC or
  add a timezone identifier explicitly.

* `yii\bootstrap\Collapse` is now encoding labels by default. `encode` item option and global `encodeLabels` property were
 introduced to disable it. Keys are no longer used as labels. You need to remove keys and use `label` item option instead.

* The `yii\base\View::beforeRender()` and `yii\base\View::afterRender()` methods have two extra parameters `$viewFile`
  and `$params`. If you are overriding these methods, you should adjust the method signature accordingly.

* If you've used `asImage` formatter i.e. `Yii::$app->formatter->asImage($value, $alt);` you should change it
  to `Yii::$app->formatter->asImage($value, ['alt' => $alt]);`.

* Yii now requires `cebe/markdown` 1.0.0 or higher, which includes breaking changes in its internal API. If you extend the markdown class
  you need to update your implementation. See <https://github.com/cebe/markdown/releases/tag/1.0.0-rc> for details.
  If you just used the markdown helper class there is no need to change anything.

* If you are using CUBRID DBMS, make sure to use at least version 9.3.0 as the server and also as the PDO extension.
  Quoting of values is broken in prior versions and Yii has no reliable way to work around this issue.
  A workaround that may have worked before has been removed in this release because it was not reliable.

Upgrade from Yii 2.0 Beta
-------------------------

* If you are using Composer to upgrade Yii, you should run the following command first (once for all) to install
  the composer-asset-plugin, *before* you update your project:

  ```
  php composer.phar global require "fxp/composer-asset-plugin:~1.3.1"
  ```

  You also need to add the following code to your project's `composer.json` file:

  ```json
  "extra": {
      "asset-installer-paths": {
          "npm-asset-library": "vendor/npm",
          "bower-asset-library": "vendor/bower"
      }
  }
  ```

  It is also a good idea to upgrade composer itself to the latest version if you see any problems:

  ```
  composer self-update
  ```

* If you used `clearAll()` or `clearAllAssignments()` of `yii\rbac\DbManager`, you should replace
  them with `removeAll()` and `removeAllAssignments()` respectively.

* If you created RBAC rule classes, you should modify their `execute()` method by adding `$user`
  as the first parameter: `execute($user, $item, $params)`. The `$user` parameter represents
  the ID of the user currently being access checked. Previously, this is passed via `$params['user']`.

* If you override `yii\grid\DataColumn::getDataCellValue()` with visibility `protected` you have
  to change visibility to `public` as visibility of the base method has changed.

* If you have classes implementing `yii\web\IdentityInterface` (very common), you should modify
  the signature of `findIdentityByAccessToken()` as
  `public static function findIdentityByAccessToken($token, $type = null)`. The new `$type` parameter
  will contain the type information about the access token. For example, if you use
  `yii\filters\auth\HttpBearerAuth` authentication method, the value of this parameter will be
  `yii\filters\auth\HttpBearerAuth`. This allows you to differentiate access tokens taken by
  different authentication methods.

* If you are sharing the same cache across different applications, you should configure
  the `keyPrefix` property of the cache component to use some unique string.
  Previously, this property was automatically assigned with a unique string.

* If you are using `dropDownList()`, `listBox()`, `activeDropDownList()`, or `activeListBox()`
  of `yii\helpers\Html`, and your list options use multiple blank spaces to format and align
  option label texts, you need to specify the option `encodeSpaces` to be true.

* If you are using `yii\grid\GridView` and have configured a data column to use a PHP callable
  to return cell values (via `yii\grid\DataColumn::value`), you may need to adjust the signature
  of the callable to be `function ($model, $key, $index, $widget)`. The `$key` parameter was newly added
  in this release.

* `yii\console\controllers\AssetController` is now using hashes instead of timestamps. Replace all `{ts}` with `{hash}`.

* The database table of the `yii\log\DbTarget` now needs a `prefix` column to store context information.
  You can add it with `ALTER TABLE log ADD COLUMN prefix TEXT AFTER log_time;`.

* The `fileinfo` PHP extension is now required by Yii. If you use  `yii\helpers\FileHelper::getMimeType()`, make sure
  you have enabled this extension. This extension is [builtin](http://www.php.net/manual/en/fileinfo.installation.php) in php above `5.3`.

* Please update your main layout file by adding this line in the `<head>` section: `<?= Html::csrfMetaTags() ?>`.
  This change is needed because `yii\web\View` no longer automatically generates CSRF meta tags due to issue #3358.

* If your model code is using the `file` validation rule, you should rename its `types` option to `extensions`.

* `MailEvent` class has been moved to the `yii\mail` namespace. You have to adjust all references that may exist in your code.

* The behavior and signature of `ActiveRecord::afterSave()` has changed. `ActiveRecord::$isNewRecord` will now always be
  false in afterSave and also dirty attributes are not available. This change has been made to have a more consistent and
  expected behavior. The changed attributes are now available in the new parameter of afterSave() `$changedAttributes`.
  `$changedAttributes` contains the old values of attributes that had changed and were saved.

* `ActiveRecord::updateAttributes()` has been changed to not trigger events and not respect optimistic locking anymore to
  differentiate it more from calling `update(false)` and to ensure it can be used in `afterSave()` without triggering infinite
  loops.

* If you are developing RESTful APIs and using an authentication method such as `yii\filters\auth\HttpBasicAuth`,
  you should explicitly configure `yii\web\User::enableSession` in the application configuration to be false to avoid
  starting a session when authentication is performed. Previously this was done automatically by authentication method.

* `mail` component was renamed to `mailer`, `yii\log\EmailTarget::$mail` was renamed to `yii\log\EmailTarget::$mailer`.
  Please update all references in the code and config files.

* `yii\caching\GroupDependency` was renamed to `TagDependency`. You should create such a dependency using the code
  `new \yii\caching\TagDependency(['tags' => 'TagName'])`, where `TagName` is similar to the group name that you
  previously used.

* If you are using the constant `YII_PATH` in your code, you should rename it to `YII2_PATH` now.

* You must explicitly configure `yii\web\Request::cookieValidationKey` with a secret key. Previously this is done automatically.
  To do so, modify your application configuration like the following:

  ```php
  return [
      // ...
      'components' => [
          'request' => [
              'cookieValidationKey' => 'your secret key here',
          ],
      ],
  ];
  ```

  > Note: If you are using the `Advanced Project Template` you should not add this configuration to `common/config`
  or `console/config` because the console application doesn't have to deal with CSRF and uses its own request that
  doesn't have `cookieValidationKey` property.

* `yii\rbac\PhpManager` now stores data in three separate files instead of one. In order to convert old file to
new ones save the following code as `convert.php` that should be placed in the same directory your `rbac.php` is in:

  ```php
  <?php
  $oldFile = 'rbac.php';
  $itemsFile = 'items.php';
  $assignmentsFile = 'assignments.php';
  $rulesFile = 'rules.php';

  $oldData = include $oldFile;

  function saveToFile($data, $fileName) {
      $out = var_export($data, true);
      $out = "<?php\nreturn " . $out . ';';
      $out = str_replace(['array (', ')'], ['[', ']'], $out);
      file_put_contents($fileName, $out, LOCK_EX);
  }

  $items = [];
  $assignments = [];
  if (isset($oldData['items'])) {
      foreach ($oldData['items'] as $name => $data) {
          if (isset($data['assignments'])) {
              foreach ($data['assignments'] as $userId => $assignmentData) {
                  $assignments[$userId][] = $assignmentData['roleName'];
              }
              unset($data['assignments']);
          }
          $items[$name] = $data;
      }
  }

  $rules = [];
  if (isset($oldData['rules'])) {
      $rules = $oldData['rules'];
  }

  saveToFile($items, $itemsFile);
  saveToFile($assignments, $assignmentsFile);
  saveToFile($rules, $rulesFile);

  echo "Done!\n";
  ```

  Run it once, delete `rbac.php`. If you've configured `authFile` property, remove the line from config and instead
  configure `itemFile`, `assignmentFile` and `ruleFile`.

* Static helper `yii\helpers\Security` has been converted into an application component. You should change all usage of
  its methods to a new syntax, for example: instead of `yii\helpers\Security::hashData()` use `Yii::$app->getSecurity()->hashData()`.
  The `generateRandomKey()` method now produces not an ASCII compatible output. Use `generateRandomString()` instead.
  Default encryption and hash parameters has been upgraded. If you need to decrypt/validate data that was encrypted/hashed
  before, use the following configuration of the 'security' component:

  ```php
  return [
      'components' => [
          'security' => [
              'derivationIterations' => 1000,
          ],
          // ...
      ],
      // ...
  ];
  ```

* If you are using query caching, you should modify your relevant code as follows, as `beginCache()` and `endCache()` are
  replaced by `cache()`:

  ```php
  $db->cache(function ($db) {

     // ... SQL queries that need to use query caching

  }, $duration, $dependency);
  ```

* Due to significant changes to security you need to upgrade your code to use `\yii\base\Security` component instead of
  helper. If you have any data encrypted it should be re-encrypted. In order to do so you can use old security helper
  [as explained by @docsolver at github](https://github.com/yiisoft/yii2/issues/4461#issuecomment-50237807).

* [[yii\helpers\Url::to()]] will no longer prefix base URL to relative URLs. For example, `Url::to('images/logo.png')`
  will return `images/logo.png` directly. If you want a relative URL to be prefix with base URL, you should make use
  of the alias `@web`. For example, `Url::to('@web/images/logo.png')` will return `/BaseUrl/images/logo.png`.

* The following properties are now taking `false` instead of `null` for "don't use" case:
  - `yii\bootstrap\NavBar::$brandLabel`.
  - `yii\bootstrap\NavBar::$brandUrl`.
  - `yii\bootstrap\Modal::$closeButton`.
  - `yii\bootstrap\Modal::$toggleButton`.
  - `yii\bootstrap\Alert::$closeButton`.
  - `yii\widgets\LinkPager::$nextPageLabel`.
  - `yii\widgets\LinkPager::$prevPageLabel`.
  - `yii\widgets\LinkPager::$firstPageLabel`.
  - `yii\widgets\LinkPager::$lastPageLabel`.

* The format of the Faker fixture template is changed. For an example, please refer to the file
  `apps/advanced/common/tests/templates/fixtures/user.php`.

* The signature of all file downloading methods in `yii\web\Response` is changed, as summarized below:
  - `sendFile($filePath, $attachmentName = null, $options = [])`
  - `sendContentAsFile($content, $attachmentName, $options = [])`
  - `sendStreamAsFile($handle, $attachmentName, $options = [])`
  - `xSendFile($filePath, $attachmentName = null, $options = [])`

* The signature of callbacks used in `yii\base\ArrayableTrait::fields()` is changed from `function ($field, $model) {`
  to `function ($model, $field) {`.

* `Html::radio()`, `Html::checkbox()`, `Html::radioList()`, `Html::checkboxList()` no longer generate the container
  tag around each radio/checkbox when you specify labels for them. You should manually render such container tags,
  or set the `item` option for `Html::radioList()`, `Html::checkboxList()` to generate the container tags.

* The formatter class has been refactored to have only one class regardless whether PHP intl extension is installed or not.
  Functionality of `yii\base\Formatter` has been merged into `yii\i18n\Formatter` and `yii\base\Formatter` has been
  removed so you have to replace all usage of `yii\base\Formatter` with `yii\i18n\Formatter` in your code.
  Also the API of the Formatter class has changed in many ways.
  The signature of the following Methods has changed:

  - `asDate`
  - `asTime`
  - `asDatetime`
  - `asSize` has been split up into `asSize` and `asShortSize`
  - `asCurrency`
  - `asDecimal`
  - `asPercent`
  - `asScientific`

  The following methods have been removed, this also means that the corresponding format which may be used by a
  GridView or DetailView is not available anymore:

  - `asNumber`
  - `asDouble`

  Also due to these changes some formatting defaults have changes so you have to check all your GridView and DetailView
  configuration and make sure the formatting is displayed correctly.

  The configuration for `asSize()` has changed. It now uses the configuration for the number formatting from intl
  and only the base is configured using `$sizeFormatBase`.

  The specification of the date and time formats is now using the ICU pattern format even if PHP intl extension is not installed.
  You can prefix a date format with `php:` to use the old format of the PHP `date()`-function.

* The DateValidator has been refactored to use the same format as the Formatter class now (see previous change).
  When you use the DateValidator and did not specify a format it will now be what is configured in the formatter class instead of 'Y-m-d'.
  To get the old behavior of the DateValidator you have to set the format explicitly in your validation rule:

  ```php
  ['attributeName', 'date', 'format' => 'php:Y-m-d'],
  ```

* `beforeValidate()`, `beforeValidateAll()`, `afterValidate()`, `afterValidateAll()`, `ajaxBeforeSend()` and `ajaxComplete()`
  are removed from `ActiveForm`. The same functionality is now achieved via JavaScript event mechanism like the following:

  ```js
  $('#myform').on('beforeValidate', function (event, messages, deferreds) {
      // called when the validation is triggered by submitting the form
      // return false if you want to cancel the validation for the whole form
  }).on('beforeValidateAttribute', function (event, attribute, messages, deferreds) {
      // before validating an attribute
      // return false if you want to cancel the validation for the attribute
  }).on('afterValidateAttribute', function (event, attribute, messages) {
      // ...
  }).on('afterValidate', function (event, messages) {
      // ...
  }).on('beforeSubmit', function () {
      // after all validations have passed
      // you can do ajax form submission here
      // return false if you want to stop form submission
  });
  ```

* The signature of `View::registerJsFile()` and `View::registerCssFile()` has changed. The `$depends` and `$position`
  paramaters have been merged into `$options`. The new signatures are as follows:

  - `registerJsFile($url, $options = [], $key = null)`
  - `registerCssFile($url, $options = [], $key = null)`
