CHANGELOG
===========

php datagrid with jquery, jquery-ui and bootstrap frontend. Simple, secure, easy to use.

Copyright Christos Pontikis http://www.pontikis.net

Project page https://github.com/pontikis/php_bs_grid

License [MIT](https://github.com/pontikis/php_bs_grid/blob/master/LICENSE)


Release 0.9.6 (16 July 2017)
--------------------------

* bug fix - multiple autocomplete failed #30
* skip_in_excel_export column option #29

Release 0.9.5 (02 July 2017)
--------------------------

* add operators to multiselect_checkbox (one or more of, not given) #22
* add operators to autocomplete (equal, not given) #23
* add not equal operator to lookup filters #27
* ability to append/prepend element in filters #26
* added ``showCriterion`` method
* less or greater operators (date, number criteria) #24
* Implement number criteria #2
* use UTF-8 for column headers in PHPExcel (column header may contain html special chars) #21
* documentation #20


Release 0.9.4 (20 June 2017)
--------------------------

* documentation #20
* Fixed where SQL #19
* pass serialized params from php to javascript #18
* Save status to SESSION #17
* Implement multiselect_checkbox criteria #11
* Implement autocomplete criteria #14
* Implement datetime criteria #15
* Fixed WHERE statements (beyond filters applied by the user interface) #13
* Added sort_simple_default_order column property #12


Release 0.9.3 (15 May 2017)
--------------------------

* trim filter values #10
* filter min chars (after trim) #9
* fix typo (th_class instead of td_class) in displayTableData #8


Release 0.9.2 (14 Apr 2017)
--------------------------

* bug fixed - Ajax error on press Enter (13) #7
* Rows per page always visible (top row) #6


Release 0.9.1 (13 Apr 2017)
--------------------------

* bugs using [is_null] operators (fixed) #5
* minified js version added


Release 0.9.0 (10 Apr 2017)
--------------------------

* Excel export added (CSV export removed) #4

Release 0.8.0 (9 Apr 2017)
--------------------------

Basic functionality #1

* Customizable columns
* Pagination
* Simple column sorting
* Advanced sorting
* Filters (text, lookup, date)
* Export (CSV)
* Multilanguage
* Support prepared statements
* Responsive design


Release 0.1.0 (3 Apr 2017)
-------------------------

* just started
