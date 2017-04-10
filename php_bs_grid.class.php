<?php

/**
 * php_bs_grid class
 *
 * php datagrid with jquery, jquery-ui and bootstrap frontend
 * https://github.com/pontikis/php_bs_grid
 *
 * @author     Christos Pontikis http://pontikis.net
 * @copyright  Christos Pontikis
 * @license    MIT http://opensource.org/licenses/MIT
 * @version    0.9.0 (10 Apr 2017)
 *
 */
class php_bs_grid {

	/**
	 * properties which passed as arguments (22)
	 */
	private $db_link;
	private $php_excel;
	private $a_columns;
	private $select_count_column;
	private $select_from_sql;
	private $show_columns_switcher;
	private $show_addnew_record;
	private $rows_per_page_options;
	private $columns_to_display_options;
	private $columns_default_icon;
	private $columns_more_icon;
	private $col_sortable_class;
	private $sort_asc_indicator;
	private $sort_desc_indicator;
	private $advanced_sorting_options;
	private $allow_export_excel;
	private $export_excel_options;
	private $export_excel_basename;
	private $main_template_path;
	private $criteria_template_path;
	private $form_action;
	private $a_strings;

	/**
	 * properties affected from user interface (7)
	 */
	private $page_num;
	private $rows_per_page;
	private $columns_to_display;
	private $sort_simple_field;
	private $sort_simple_order;
	private $sort_advanced;
	private $export_excel;

	/**
	 * other properties (14)
	 */
	// will take values when setCriteria($arr) is called
	private $a_criteria = array();

	// will take values when _createSelectSQL() is called
	private $select_sql = null;

	// will take values when _createWhereSQL() is called
	private $where_sql = null;
	private $bind_params = array();
	private $filters_applied = 0;

	// will take values when _createSortingSQL() is called
	private $sorting_sql = null;

	// will take values when _createLimitSQL() is called
	private $limit_sql = null;

	// will take values when _countTotalRows() is called
	private $total_rows = null;
	private $total_pages = null;

	// will take values when retrieveDbData() is called
	private $db_data = array();

	// will take values when setDbDataFormatted() is called
	private $db_data_formatted = array();

	// will take values when getFirstRowNumInPage() is called
	private $first_row_num_in_page = null;

	// will take values when getLastRowNumInPage() is called
	private $last_row_num_in_page = null;

	private $last_error = null;

	/**
	 * php_bs_grid constructor.
	 * @param dacapo $db_link
	 * @param PHPExcel $phpexel
	 * @param array $dg_params
	 */
	public function __construct(dacapo $db_link, PHPExcel $php_excel, array $dg_params) {
		/**
		 * properties which passed as arguments (22)
		 */
		$this->db_link = $db_link;
		$this->php_excel = $php_excel;
		$this->a_columns = $dg_params['dg_columns'];
		$this->select_count_column = $dg_params['dg_select_count_column'];
		$this->select_from_sql = $dg_params['dg_select_from_sql'];
		$this->show_columns_switcher = $dg_params['dg_show_columns_switcher'];
		$this->show_addnew_record = $dg_params['dg_show_addnew_record'];
		$this->rows_per_page_options = $dg_params['dg_rows_per_page_options'];
		$this->columns_to_display_options = $dg_params['dg_columns_to_display_options'];
		$this->columns_default_icon = $dg_params['dg_columns_default_icon'];
		$this->columns_more_icon = $dg_params['dg_columns_more_icon'];
		$this->col_sortable_class = $dg_params['dg_col_sortable_class'];
		$this->sort_asc_indicator = $dg_params['dg_sort_asc_indicator'];
		$this->sort_desc_indicator = $dg_params['dg_sort_desc_indicator'];
		$this->advanced_sorting_options = $dg_params['dg_advanced_sorting_options'];
		$this->allow_export_excel = $dg_params['dg_allow_export_excel'];
		$this->export_excel_options = $dg_params['dg_export_excel_options'];
		$this->export_excel_basename = $dg_params['dg_export_excel_basename'];
		$this->main_template_path = $dg_params['dg_main_template_path'];
		$this->criteria_template_path = $dg_params['dg_criteria_template_path'];
		$this->form_action = $dg_params['dg_form_action'];
		$this->a_strings = $dg_params['dg_strings'];

		/**
		 * properties affected from user interface (7)
		 */
		$this->page_num = $this->sanitize_int('page_num', C_PHP_BS_GRID_DEFAULT_PAGE_NUM, null, null);
		$this->rows_per_page = $this->sanitize_int('rows_per_page', C_PHP_BS_GRID_DEFAULT_ROWS_PER_PAGE, $this->rows_per_page_options, null);
		$this->columns_to_display = $this->sanitize_int('columns_to_display', C_PHP_BS_GRID_COLUMNS_DEFAULT, $this->columns_to_display_options, null);

		$this->sort_simple_field = null;
		if(isset($_POST['sort_simple_field'])) {
			if(array_key_exists($_POST['sort_simple_field'], $this->a_columns)) {
				if($this->a_columns[$_POST['sort_simple_field']]['sort_simple']) {
					$this->sort_simple_field = $_POST['sort_simple_field'];
				}
			}
		}

		$this->sort_simple_order = null;
		if(isset($_POST['sort_simple_order'])) {
			if(in_array($_POST['sort_simple_order'], array('ASC', 'DESC'))) {
				$this->sort_simple_order = $_POST['sort_simple_order'];
			} else {
				$this->sort_simple_field = null;
			}
		}

		if(!$this->advanced_sorting_options) {
			if(!$this->sort_simple_field) {
				foreach($this->a_columns as $key => $column) {
					if(array_key_exists('sort_simple_default', $column) &&
						$column['sort_simple_default'] === true
					) {
						$this->sort_simple_field = $key;
						$this->sort_simple_order = 'ASC';
						break;
					}
				}
			}
		} else {
			if($this->sort_simple_field) {
				$this->sort_advanced = C_PHP_BS_GRID_SHORT_ADVANCED_IGNORE;
			} else {
				$this->sort_advanced = $this->sanitize_int('sort_advanced', C_PHP_BS_GRID_SHORT_ADVANCED_DEFAULT, null, $this->advanced_sorting_options);
			}
		}

		$this->export_excel = $this->sanitize_int('export_excel', C_PHP_BS_GRID_EXPORT_EXCEL_NO, $this->export_excel_options, null);

	}

	// getters -----------------------------------------------------------------
	public function getPageNum() {
		return $this->page_num;
	}

	public function getColumnsToDisplay() {
		return $this->columns_to_display;
	}

	public function getColumnsToDisplayIcon() {
		return $this->columns_to_display === C_PHP_BS_GRID_COLUMNS_MORE ? $this->columns_more_icon : $this->columns_default_icon;
	}

	public function getColumnsToDisplayText() {
		return $this->columns_to_display === C_PHP_BS_GRID_COLUMNS_MORE ? $this->a_strings['columns_more'] : $this->a_strings['columns_default'];
	}

	public function getAdvancedSortingOptions() {
		return $this->advanced_sorting_options;
	}

	public function getSortSimpleField() {
		return $this->sort_simple_field;
	}

	public function getSortSimpleOrder() {
		return $this->sort_simple_order;
	}

	public function getExportExcel() {
		return $this->export_excel;
	}

	public function getDbData() {
		return $this->db_data;
	}

	public function getTotalRows() {
		return $this->total_rows;
	}

	public function getTotalPages() {
		return $this->total_pages;
	}

	public function getFirstRowNumInPage() {
		$this->first_row_num_in_page = (($this->page_num - 1) * $this->rows_per_page) + 1;
		return $this->first_row_num_in_page;
	}

	public function getLastRowNumInPage() {
		$last_row = $this->getFirstRowNumInPage() + $this->rows_per_page - 1;
		if($this->page_num == $this->total_pages) {
			$last_row = $this->total_rows;
		}
		$this->last_row_num_in_page = $last_row;
		return $last_row;
	}

	public function getMainTemplatePath() {
		return $this->main_template_path;
	}

	public function getCriteriaTemplatePath() {
		return $this->criteria_template_path;
	}

	public function getFiltersAppliedCount() {
		return $this->filters_applied;
	}

	public function getFormAction() {
		return urldecode($this->form_action);
	}

	public function getString($key_string) {
		$result = '';
		if(array_key_exists($key_string, $this->a_strings)) {
			$result = $this->a_strings[$key_string];
		}
		return $result;
	}

	public function getError() {
		return $this->last_error;
	}

	// get preferences ---------------------------------------------------------
	public function showColumnsSwitcher() {
		return $this->show_columns_switcher;
	}

	public function showAddnewRecord() {
		return $this->show_addnew_record;
	}

	public function allowExportExcel() {
		return $this->allow_export_excel;
	}

	// setters -----------------------------------------------------------------
	public function setCriteria($a_criteria) {
		$this->a_criteria = $a_criteria;
	}

	public function setDbDataFormatted($a_db_data) {
		$this->db_data_formatted = $a_db_data;
	}

	/**
	 * This applies query criteria (WHERE SQL)
	 * counts rows returned
	 * and retrieves current page data (to display)
	 * or all rows returned (for Excel export)
	 * @return bool
	 */
	public function retrieveDbData() {
		$db_data = array();
		$this->_createWhereSQL();
		if(!$this->_countTotalRows()) {
			return false;
		}
		if($this->total_rows) {
			$ds = $this->db_link;
			$this->_createSelectSQL();
			$this->_createSortingSQL();
			$this->_createLimitSQL();
			$sql = $this->select_sql . ' ' . $this->where_sql . ' ' . $this->sorting_sql . ' ' . $this->limit_sql;
			$res = $ds->select($sql, $this->bind_params);
			if(!$res) {
				$this->last_error = $ds->last_error;
				return false;
			} else {
				$db_data = $ds->data;
			}
		}
		$this->db_data = $db_data;
		return true;
	}

	public function displayTableHeaders() {

		$a_th = array();
		foreach($this->a_columns as $key => $column) {
			if(array_key_exists('is_hidden', $column) && $column['is_hidden'] === true) {
				continue;
			}
			if($this->columns_to_display === C_PHP_BS_GRID_COLUMNS_DEFAULT && $column['display'] === C_PHP_BS_GRID_COLUMNS_MORE) {
				continue;
			}

			$class = '';
			if(array_key_exists('th_class', $column) && $column['th_class']) {
				$class .= $column['th_class'];
			}
			if(array_key_exists('sort_simple', $column) && $column['sort_simple'] === true) {
				if($class) {
					$class .= ' ';
				}
				$class .= $this->col_sortable_class;
			}
			if($class) {
				$class = ' class="' . $class . '"';
			}

			$sort_indicator = '';
			if(array_key_exists('sort_simple', $column) && $column['sort_simple'] === true) {
				if($this->sort_simple_field === $key) {
					$sort_indicator = ($this->sort_simple_order === 'ASC' ? $this->sort_asc_indicator : $this->sort_desc_indicator);
				}
			}

			$a_tmp = array(
				'id' => $key,
				'class' => $class,
				'sort_indicator' => $sort_indicator,
				'header' => $column['header'],
			);
			array_push($a_th, $a_tmp);

		}

		foreach($a_th as $item) {
			echo '<th id="' . $item['id'] . '"' . $item['class'] . '>' . $item['sort_indicator'] . $item['header'] . '</th>';
		}

	}

	public function displayTableData() {

		$row_num = $this->getFirstRowNumInPage();
		$a_td = array();

		foreach($this->db_data_formatted as $row) {

			$a_tmp_row = array();

			$a_tmp_cell = array(
				'class' => '',
				'data' => $row_num++
			);
			array_push($a_tmp_row, $a_tmp_cell);

			foreach($this->a_columns as $key => $column) {
				if(array_key_exists('is_hidden', $column) && $column['is_hidden'] === true) {
					continue;
				}
				if($this->columns_to_display === C_PHP_BS_GRID_COLUMNS_DEFAULT && $column['display'] === C_PHP_BS_GRID_COLUMNS_MORE) {
					continue;
				}

				$class = '';
				if(array_key_exists('td_class', $column) && $column['td_class']) {
					$class = ' class="' . $column['th_class'] . '"';
				}

				$a_tmp_cell = array(
					'class' => $class,
					'data' => $row[$key]
				);
				array_push($a_tmp_row, $a_tmp_cell);
			}

			array_push($a_td, $a_tmp_row);
		}

		foreach($a_td as $row) {
			echo '<tr>';
			foreach($row as $key => $cell) {
				if($key == 0) {
					echo '<th>' . $cell['data'] . '</th>';
				} else {
					echo '<td' . $cell['class'] . '>' . $cell['data'] . '</td>';
				}
			}
			echo '</tr>';
		}

	}


	public function displayRowsPerPage() {
		foreach($this->rows_per_page_options as $item) {
			echo '<option' . ($this->rows_per_page == $item ? ' selected' : '') . ' value="' . $item . '">' . $item . '</option>';
		}
	}

	public function displayAdvancedSorting() {
		foreach($this->getAdvancedSortingOptions() as $key => $item) {
			if($key == 1 && !$this->getSortSimpleField()) {
				continue;
			}
			echo '<option' . ($this->sort_advanced == $key ? ' selected' : '') . ' value="' . $key . '">' . $item['text'] . '</option>';
		}
	}

	/**
	 * @param $criterion
	 * @return string
	 */
	public function displayCriteriaText($criterion) {

		$params = $this->a_criteria[$criterion]['params_html'];

		// get data from params array
		$wrapper_div_class = $params['wrapper_div_class'];

		$label = $params['label'];
		$label_id = $params['label_id'];
		$label_class = $params['label_class'];

		$dropdown_id = $params['dropdown_id'];
		$dropdown_name = $params['dropdown_name'];
		$dropdown_class = $params['dropdown_class'];
		$dropdown_options = $params['dropdown_options'];
		$dropdown_value = $params['dropdown_value'];

		$input_id = $params['input_id'];
		$input_name = $params['input_name'];
		$input_class = $params['input_class'];
		$maxlength = $params['maxlength'];
		$autocomplete = $params['autocomplete'];
		$input_value = $params['input_value'];

		// some transformations
		$label_id_html = !$label_id ? '' : ' id="' . $label_id . '"';
		$label_class_html = !$label_class ? '' : ' class="' . $label_class . '"';

		$maxlength_html = !$maxlength ? '' : 'maxlength="' . $maxlength . '"';
		$autocomplete_html = $autocomplete ? '' : 'autocomplete="off"';
		$input_value = htmlspecialchars($input_value);

		// create dropdown options html
		$dropdown_options_html = '';
		foreach($dropdown_options as $key => $item) {
			$dropdown_options_html .=
				'<option' . ($dropdown_value == $key ? ' selected' : '') . ' value="' . $key . '">' .
				$item .
				'</option>' . PHP_EOL;
		}

		$html1 = <<<HTML1
<div class="{$wrapper_div_class}">
	<label{$label_id_html}{$label_class_html} for="{$input_id}">{$label}</label>

	<select id="{$dropdown_id}"
			name="{$dropdown_name}"
			class="{$dropdown_class}">
			{$dropdown_options_html}
	</select>

	<input type="text"
		   id="{$input_id}"
		   name="{$input_name}"
		   class="{$input_class}"
		   {$maxlength_html}
		   {$autocomplete_html}
		   value="{$input_value}">
</div>
HTML1;

		return $html1;
	}


	/**
	 * @param $criterion
	 * @return string
	 */
	public function displayCriteriaLookup($criterion) {

		$params = $this->a_criteria[$criterion]['params_html'];

		// get data from params array
		$wrapper_div_class = $params['wrapper_div_class'];

		$label = $params['label'];
		$label_id = $params['label_id'];
		$label_class = $params['label_class'];

		$dropdown_id = $params['dropdown_id'];
		$dropdown_name = $params['dropdown_name'];
		$dropdown_class = $params['dropdown_class'];
		$dropdown_options = $params['dropdown_options'];
		$dropdown_value = $params['dropdown_value'];

		$dropdown_lookup_id = $params['dropdown_lookup_id'];
		$dropdown_lookup_name = $params['dropdown_lookup_name'];
		$dropdown_lookup_class = $params['dropdown_lookup_class'];
		$dropdown_lookup_options = $params['dropdown_lookup_options'];
		$dropdown_lookup_value = $params['dropdown_lookup_value'];

		// some transformations
		$label_id_html = !$label_id ? '' : ' id="' . $label_id . '"';
		$label_class_html = !$label_class ? '' : ' class="' . $label_class . '"';

		// create dropdown options html
		$dropdown_options_html = '';
		foreach($dropdown_options as $key => $item) {
			$dropdown_options_html .=
				'<option' . ($dropdown_value == $key ? ' selected' : '') . ' value="' . $key . '">' .
				$item .
				'</option>' . PHP_EOL;
		}

		// create dropdown lookup options html
		$dropdown_lookup_options_html = '';
		foreach($dropdown_lookup_options as $key => $item) {
			$dropdown_lookup_options_html .=
				'<option' . ($dropdown_lookup_value == $key ? ' selected' : '') . ' value="' . $key . '">' .
				$item .
				'</option>' . PHP_EOL;
		}


		$html1 = <<<HTML1
<div class="{$wrapper_div_class}">
	<label{$label_id_html}{$label_class_html} for="{$dropdown_lookup_id}">{$label}</label>

	<select id="{$dropdown_id}"
			name="{$dropdown_name}"
			class="{$dropdown_class}">
			{$dropdown_options_html}
	</select>

	<select id="{$dropdown_lookup_id}"
			name="{$dropdown_lookup_name}"
			class="{$dropdown_lookup_class}">
			{$dropdown_lookup_options_html}
	</select>
	
</div>
HTML1;

		return $html1;
	}


	/**
	 * @param $criterion
	 * @return string
	 */
	public function displayCriteriaDate($criterion) {

		$params = $this->a_criteria[$criterion]['params_html'];

		// get data from params array
		$wrapper_div_class = $params['wrapper_div_class'];

		$label = $params['label'];
		$label_id = $params['label_id'];
		$label_class = $params['label_class'];

		$dropdown_id = $params['dropdown_id'];
		$dropdown_name = $params['dropdown_name'];
		$dropdown_class = $params['dropdown_class'];
		$dropdown_options = $params['dropdown_options'];
		$dropdown_value = $params['dropdown_value'];

		$input_id = $params['input_id'];
		$input_name = $params['input_name'];
		$input_class = $params['input_class'];
		$maxlength = $params['maxlength'];
		$autocomplete = $params['autocomplete'];
		$input_value = $params['input_value'];

		// some transformations
		$label_id_html = !$label_id ? '' : ' id="' . $label_id . '"';
		$label_class_html = !$label_class ? '' : ' class="' . $label_class . '"';

		$maxlength_html = !$maxlength ? '' : 'maxlength="' . $maxlength . '"';
		$autocomplete_html = $autocomplete ? '' : 'autocomplete="off"';
		$input_value = htmlspecialchars($input_value);

		// create dropdown options html
		$dropdown_options_html = '';
		foreach($dropdown_options as $key => $item) {
			$dropdown_options_html .=
				'<option' . ($dropdown_value == $key ? ' selected' : '') . ' value="' . $key . '">' .
				$item .
				'</option>' . PHP_EOL;
		}

		$html1 = <<<HTML1
<div class="{$wrapper_div_class}">
	<label{$label_id_html}{$label_class_html} for="{$input_id}">{$label}</label>

	<select id="{$dropdown_id}"
			name="{$dropdown_name}"
			class="{$dropdown_class}">
			{$dropdown_options_html}
	</select>

	<input type="text"
		   id="{$input_id}"
		   name="{$input_name}"
		   class="{$input_class}"
		   {$maxlength_html}
		   {$autocomplete_html}
		   value="{$input_value}">
</div>
HTML1;

		return $html1;
	}

	/**
	 * @return bool
	 */
	public function exportExcel() {

		try {

			// get columns for excel export and some properties
			$a_excel_columns = array();
			foreach($this->a_columns as $key => $column) {
				if(array_key_exists('is_hidden', $column) && $column['is_hidden'] === true) {
					continue;
				}
				if($this->columns_to_display === C_PHP_BS_GRID_COLUMNS_DEFAULT && $column['display'] === C_PHP_BS_GRID_COLUMNS_MORE) {
					continue;
				}
				$excel_export_number_as_string = false;
				if(array_key_exists('excel_export_number_as_string', $column)) {
					$excel_export_number_as_string = $column['excel_export_number_as_string'];
				}
				array_push($a_excel_columns, array(
					'header' => $column['header'],
					'column_name' => $key,
					'excel_export_number_as_string' => $excel_export_number_as_string
				));
			}

			$objPHPExcel = $this->php_excel;
			$objPHPExcel->getActiveSheet()->setTitle($this->export_excel_basename);

			// header
			$column_index = 0;
			foreach($a_excel_columns as $excel_column) {
				$columnLetter = PHPExcel_Cell::stringFromColumnIndex($column_index);
				$objPHPExcel->getActiveSheet()->setCellValue($columnLetter . '1', $excel_column['header']);
				$column_index++;
			}

			// rows
			$row_index = 2;
			foreach($this->db_data_formatted as $row) {
				$column_index = 0;
				foreach($a_excel_columns as $excel_column) {
					$columnLetter = PHPExcel_Cell::stringFromColumnIndex($column_index);
					if($excel_column['excel_export_number_as_string'] === true) {
						$objPHPExcel->getActiveSheet()->setCellValueExplicit($columnLetter . $row_index, $row[$excel_column['column_name']], PHPExcel_Cell_DataType::TYPE_STRING);
					} else {
						$objPHPExcel->getActiveSheet()->setCellValue($columnLetter . $row_index, $row[$excel_column['column_name']]);
					}
					$column_index++;
				}
				$row_index++;
			}

			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="' . $this->export_excel_basename . '.xlsx' . '"');
			header('Cache-Control: max-age=0');
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');

			return true;

		} catch(Exception $e) {
			$this->last_error = $e->getMessage();
			return false;
		}


	}

	public function sanitize_int($param, $default_value, $arr_to_belong, $arr_has_key) {
		$result = $default_value;
		if(isset($_POST[$param])) {
			if($this->_is_positive_integer($_POST[$param])) {
				$tmp = $this->_trim_integer($_POST[$param]);
				$result = (int)$tmp;

				if($arr_to_belong) {
					if(!in_array($tmp, $arr_to_belong)) {
						$result = $default_value;
					}
				}

				if($arr_has_key) {
					if(!array_key_exists($tmp, $arr_has_key)) {
						$result = $default_value;
					}
				}

			}
		}
		return $result;
	}

	/**
	 *
	 */
	private function _createSelectSQL() {

		$select = 'SELECT ';
		foreach($this->a_columns as $column) {
			if(!array_key_exists('select_sql', $column)) {
				continue;
			}
			if($this->columns_to_display === C_PHP_BS_GRID_COLUMNS_DEFAULT && $column['display'] === C_PHP_BS_GRID_COLUMNS_MORE) {
				continue;
			}
			$select .= $column['select_sql'] . ', ';
		}
		$select = rtrim($select, ', ') . ' ' . $this->select_from_sql;

		$this->select_sql = $select;

	}

	/**
	 *
	 */
	private function _createSortingSQL() {
		$sortingSQL = '';

		if($this->sort_simple_field) {
			switch($this->sort_simple_order) {
				case 'ASC':
					if(array_key_exists('sorting_sql_asc', $this->a_columns[$this->sort_simple_field])) {
						$sortingSQL = $this->a_columns[$this->sort_simple_field]['sorting_sql_asc'];
					} else {
						$sortingSQL = 'ORDER BY ' . $this->sort_simple_field . ' ASC';
					}
					break;
				case 'DESC':
					if(array_key_exists('sorting_sql_desc', $this->a_columns[$this->sort_simple_field])) {
						$sortingSQL = $this->a_columns[$this->sort_simple_field]['sorting_sql_desc'];
					} else {
						$sortingSQL = 'ORDER BY ' . $this->sort_simple_field . ' DESC';
					}
					break;
			}
		} else {
			$sortingSQL = $this->advanced_sorting_options[$this->sort_advanced]['sql'];
		}

		$this->sorting_sql = $sortingSQL;

	}


	/**
	 *
	 */
	private function _createWhereSQL() {

		$whereSQL = '';
		$a_whereSQL = array();
		$bind_params = array();

		foreach($this->a_criteria as $criterion) {

			switch($criterion['type']) {

				case 'text':
					switch($criterion['sql_comparison_operator']) {
						case C_PHP_BS_GRID_CRITERIA_TEXT_IGNORE:
							break;
						case C_PHP_BS_GRID_CRITERIA_TEXT_EQUAL:
							array_push($a_whereSQL, $criterion['sql_column'] . ' = ?');
							array_push($bind_params, $criterion['column_value']);
							break;
						case C_PHP_BS_GRID_CRITERIA_TEXT_STARTS_WITH:
							array_push($a_whereSQL, $criterion['sql_column'] . ' LIKE ?');
							array_push($bind_params, $criterion['column_value'] . '%');
							break;
						case C_PHP_BS_GRID_CRITERIA_TEXT_CONTAINS:
							array_push($a_whereSQL, $criterion['sql_column'] . ' LIKE ?');
							array_push($bind_params, '%' . $criterion['column_value'] . '%');
							break;
						case C_PHP_BS_GRID_CRITERIA_TEXT_IS_NULL:
							array_push($a_whereSQL, $criterion['sql_column'] . ' IS NULL');
							break;
					}
					break;

				case 'lookup':
					switch($criterion['sql_comparison_operator']) {
						case C_PHP_BS_GRID_CRITERIA_LOOKUP_IGNORE:
							break;
						case C_PHP_BS_GRID_CRITERIA_LOOKUP_EQUAL:
							array_push($a_whereSQL, $criterion['sql_column'] . ' = ?');
							array_push($bind_params, $criterion['column_value']);
							break;
						case C_PHP_BS_GRID_CRITERIA_LOOKUP_IS_NULL:
							array_push($a_whereSQL, $criterion['sql_column'] . ' IS NULL');
							break;
					}
					break;

				case 'date_start':
					switch($criterion['sql_comparison_operator']) {
						case C_PHP_BS_GRID_CRITERIA_DATE_IGNORE:
							break;
						case C_PHP_BS_GRID_CRITERIA_DATE_EQUAL:
							array_push($a_whereSQL, $criterion['sql_column'] . ' = ?');
							array_push($bind_params, $criterion['column_value']);
							break;
						case C_PHP_BS_GRID_CRITERIA_DATE_GREATER_THAN_OR_EQUAL_TO:
							array_push($a_whereSQL, $criterion['sql_column'] . ' >= ?');
							array_push($bind_params, $criterion['column_value']);
							break;
						case C_PHP_BS_GRID_CRITERIA_DATE_IS_NULL:
							array_push($a_whereSQL, $criterion['sql_column'] . ' IS NULL');
							break;
					}
					break;

				case 'date_end':
					switch($criterion['sql_comparison_operator']) {
						case C_PHP_BS_GRID_CRITERIA_DATE_IGNORE:
							break;
						case C_PHP_BS_GRID_CRITERIA_DATE_LESS_THAN_OR_EQUAL_TO:
							array_push($a_whereSQL, $criterion['sql_column'] . ' <= ?');
							array_push($bind_params, $criterion['column_value']);
							break;
						case C_PHP_BS_GRID_CRITERIA_DATE_IS_NULL:
							array_push($a_whereSQL, $criterion['sql_column'] . ' IS NULL');
							break;
					}
					break;
			}
		}

		$filters_applied = count($a_whereSQL);
		if($filters_applied) {
			$whereSQL = 'WHERE ';
		}
		foreach($a_whereSQL as $key => $filter) {
			$whereSQL .= $filter;
			if($key < $filters_applied - 1)
				$whereSQL .= ' AND ';
		}

		$this->where_sql = $whereSQL;
		$this->bind_params = $bind_params;
		$this->filters_applied = $filters_applied;

	}

	/**
	 * Count rows returned from current query
	 * @return bool
	 */
	private function _countTotalRows() {
		$ds = $this->db_link;
		$selectCountSQL = 'SELECT count(' . $this->select_count_column . ') as totalrows ' . $this->select_from_sql;
		$sql = $selectCountSQL . ' ' . $this->where_sql;

		$query_options = array('get_row' => true);
		$res = $ds->select($sql, $this->bind_params, $query_options);
		if(!$res) {
			$this->last_error = $ds->last_error;
			return false;
		}
		$rs = $ds->data;
		$this->total_rows = $rs['totalrows'];
		$this->total_pages = ceil($this->total_rows / $this->rows_per_page);

		if($this->page_num > $this->total_pages) {
			$this->page_num = $this->total_pages;
		}
		return true;
	}


	private function _createLimitSQL() {
		$limitSQL = '';
		if($this->export_excel === C_PHP_BS_GRID_EXPORT_EXCEL_NO) {
			$ds = $this->db_link;
			$limitSQL = $ds->limit($this->rows_per_page, ($this->page_num - 1) * $this->rows_per_page);
		}
		$this->limit_sql = $limitSQL;
	}

	private function _is_positive_integer($str) {
		return (is_numeric($str) && $str > 0 && $str == round($str));
	}

	private function _trim_integer($str) {
		return ltrim(trim($str), "0");
	}

}