# php_bs_grid

php datagrid with jquery, jquery-ui and bootstrap frontend

Project page https://github.com/pontikis/php_bs_grid

Simple, secure, easy to use.

## Features

Displays database data in table format.

* Customizable columns
* Pagination
* Simple column sorting
* Advanced sorting
* Filters 
    * text 
    * lookup 
    * date 
    * autocomplete 
    * multiselect_checkbox
* Export (Excel .xlsx)
* Multilanguage
* Databases supported: MySQL (or MariaDB), PostgreSQL
* Prepared statements supported
* Fixed WHERE sql supported
* Responsive design
* Fully customizable (open architecture based on templates) 
* Save status to $_SESSION

## Dependencies

### back-end
* tested with php 5.6 and php 7
* dacapo (database abstraction - MySQL, MariaDB, PostGreSQL) - https://github.com/pontikis/dacapo
* PHPExcel is required for export https://github.com/PHPOffice/PHPExcel

### front-end
* jquery https://jquery.com/ (tested with v3.1.1)
* jquery-ui (datepicker, autocomplete) http://jqueryui.com/ (tested with v1.12.1)
* jQuery UI Autocomplete HTML Extension http://github.com/scottgonzalez/jquery-ui-extensions (optional)
* jQuery-Timepicker-Addon http://trentrichardson.com/examples/timepicker/ (tested with v1.6.3)
* twitter bootstrap http://getbootstrap.com/ (tested with v3.3.7)

## Files
 
1. ``php_bs_grid.class.php`` php class
2. ``jquery.php_bs_grid.js`` jquery plugin (minified version also available for production)
3. ``constants.php`` php constants to include
4. ``template.php`` default template
5. ``php_bs_grid.css`` default css file

## Documentation

See ``docs/doxygen/html`` for html documentation of ``php_bs_grid`` class. 

See ``docs/jsdoc`` for html documentation of ``php_bs_grid`` jquery plugin.

See also Github Wiki https://github.com/pontikis/php_bs_grid/wiki

## How to use

See ``examples`` folder.

In ``examples/example_common`` folder find an example where a hidden field (``dg_status``) is used to pass the serialized status of ``php_bs_grid`` to javascript using an ajax call (``ajax_get_vars.php``).

In ``examples/example_using_session`` folder find an example where ``$_SESSION`` is used to keep the status of ``php_bs_grid``. You can pass ``php_bs_grid`` parameters to javascript using an ajax call (``ajax_get_vars.php``). Moreover, you can return to recent status (filters, pagination etc) after returning to datagrid page from another page. This is the recommended use of `php_bs_grid``.

Custom functions included in ``examples/common/util_functions.php``. They have **nothing to do with** ``php_bs_grid`` functionality. I quote them just for rerefrence. 

## See it in action

Coming soon at http://www.pontikis.net/labs

## Screenshots

Desktop:

![Desktop](https://raw.githubusercontent.com/pontikis/php_bs_grid/master/screenshots/desktop.png)

Nexus 5:

![Desktop](https://raw.githubusercontent.com/pontikis/php_bs_grid/master/screenshots/Nexus-5X.png)

iPhone 6:

![Desktop](https://raw.githubusercontent.com/pontikis/php_bs_grid/master/screenshots/iPhone-6.png)

iPad:

![Desktop](https://raw.githubusercontent.com/pontikis/php_bs_grid/master/screenshots/iPad.png)
