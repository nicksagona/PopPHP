Pop PHP Framework
=================

Documentation : Overview
------------------------

流行的PHP框架是一个面向对象的PHP框架，将用一个简单的，使用的API允许你利用广泛的功能。您可以使用它作为一个工具箱，以协助迅速编写基本的脚本，或者你可以使用它作为一个完整的框架，建立和大规模定制，强大的应用程序。在该框架的核心是一组元件，其中一些可独立使用，有的可利用的框架和PHP的全功率串联使用。


* Archive
* Auth
* Cache
* Cli
* Code
* Color
* Compress
* Config
* Curl
* Data
* Db
* Dir
* Dom
* Feed
* File
* Filter
* Font
* Form
* Ftp
* Geo
* Graph
* Http
* Image
* Loader
* Locale
* Mail
* Mvc
* Paginator
* Payment
* Pdf
* Project
* Record
* Validator
* Version
* Web

快速入门

----------

有两种方法，你可以起床和运行流行的PHP框架。


如果你只是写一些快速的脚本，你可以简单地拖放到你的工作项目文件夹中的源文件夹，在脚本中引用“bootstrap.php”据此，并开始编写代码。你会发现所有在本文档将解释不同的组件，以及如何你可以用它们的引用和例子。


If you're looking to build a larger-scale application, you can use the CLI component to create the project's base foundation, or scaffolding. This way, you can start writing project code quickly and not have to burdened with getting everything up and running. All you have to do is define your project in single installation file, run the Pop CLI command using that file and - voila! - Pop does all the dirty work for you and you can get to writing project code faster. Review the documentation on the CLI component to further explore how to take advantage of this robust component.

MVC组件

-----------------

MVC组件和建设一个大型的应用程序时特别有用。 MVC的模型 - 视图 - 控制器和代表的关注，有利于有组织的分离是一种设计模式。它可以让你的演讲，业务逻辑和数据访问都可以分开存放。


The controller receives input (i.e. a web request) from the user and based on that input, communicates that with the model. The model can then process the request to determine what data or response is needed. At that point, the model and view communicate so that the view can build the presentation, or view, based on the data obtained from the model. Then, the controller will communicate with the view to display the appropriate output to the user.

One extra piece of the MVC component that is available with the Pop PHP Framework is a router. The router is simply an additional layer on top that does exactly what its name suggests  it routes different types of user requests to their corresponding controllers. In other words, it provides an easy way to manage multiple user paths and controllers.

很多时候，它可以是很难掌握MVC设计模式，直到你真正开始使用它。一旦你这样做，虽然，你会立即看到一切在易于管理的概念与分离出来的很少，如果有的话，重叠的利益。您的控制器处理代表团的请求，你的模型处理业务逻辑和你的看法决定如何显示给用户的输出。到目前为止，这个模式胜过一切塞进一个脚本或各种脚本，创建一个大混乱的地方都包括昔日。只是尝试一下，你会看到！


DB记录组件

--------------------------

DB和记录组件由两部分组成，有可能用于在任何应用中颇有几分。显然，DB组件提供查询数据库的直接访问。支持的适配器包括本地MySQL的MySQLi，PGSQL时，SQLite和PDO。他们服务正常化在不同环境下的数据库访问，让你不必担心重新加工的应用程序，工作在不同的环境不同类型的数据库。


记录组件是一个功能强大的组件，它提供标准化的访问数据库内的数据，特别是数据库表和表内的个人记录。记录组件是真正的活动记录表数据网关模式的混合体。像一个Active Record模式，在同一时间或多个行就像一个表数据网关，它可以提供一个单一的行或记录的访问。与流行的PHP框架，最常用的方法是写一个子类，扩展类，它代表了数据库中的表的记录。子类的名称应该是表的名称。通过简单地创建


<pre>
use Pop\Record\Record;

class Users extends Record { }
</pre>

you create a class that has all of the functionality of the Record component built in and the class knows the name of the database table to query from the class name. For example,  'Users' translates into `users` or 'DbUsers' translates into `db_users` (CamelCase is automatically converted into lower_case_underscore.) Review the Record documentation to see how you can fine tune the child table class.

(c) 2009-2012 [Moc 10 Media, LLC.](http://www.moc10media.com) All Rights Reserved.