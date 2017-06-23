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
 * @version    0.9.5 (XX Jun 2017)
 *
 */
class php_bs_grid {

	/**
	 * properties which passed as arguments (2)
	 */
	private $db_link;
	private $php_excel;

	/**
	 * properties which passed as array of arguments (36)
	 */
	// general (7)
	private $name;
	private $form_action;
	private $ajax_validate_form_url;
	private $ajax_reset_all_url;
	private $a_strings;
	private $bs_modal_id;
	private $bs_modal_content_id;
	// sql (6)
	private $a_columns;
	private $select_count_column;
	private $select_from_sql;
	private $select_from_sql_more;
	private $a_fixed_where;
	private $a_fixed_bind_params;
	// grid (22)
	private $rows_per_page_options;
	private $rows_per_page;

	private $show_columns_switcher;
	private $columns_to_display_options;
	private $columns_default_icon;
	private $columns_more_icon;
	private $columns_to_display;

	private $show_addnew_record;
	private $addnew_record_url;

	private $col_sortable_class;
	private $sort_asc_indicator;
	private $sort_desc_indicator;
	private $advanced_sorting_options;
	private $sort_simple_field;
	private $sort_simple_order;
	private $sort_advanced;

	private $page_num;

	private $allow_export_excel;
	private $export_excel_options;
	private $export_excel_basename;
	private $export_excel;

	private $grid_template_path;
	// criteria (2)
	private $a_criteria;
	private $criteria_template_path;

	/**
	 * other properties (13)
	 */
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

	// will take values when retrieveDbData() or setDbDataFormatted() is called
	private $db_data_formatted = array();

	// will take values when getFirstRowNumInPage() is called
	private $first_row_num_in_page = null;

	// will take values when getLastRowNumInPage() is called
	private $last_row_num_in_page = null;

	private $last_error = null;

	/**
	 * php_bs_grid constructor.
	 * @param dacapo $db_link
	 * @param PHPExcel $php_excel
	 * @param array $dg_params
	 */
	public function __construct(dacapo $db_link, PHPExcel $php_excel, array $dg_params) {
		/**
		 * properties which passed as arguments (2)
		 */
		$this->db_link = $db_link;
		$this->php_excel = $php_excel;

		/**
		 * properties which passed as array of arguments (36)
		 */
		// general (7)
		$this->name = $dg_params['dg_name'];
		$this->form_action = $dg_params['dg_form_action'];
		$this->ajax_validate_form_url = $dg_params['dg_ajax_validate_form_url'];
		$this->ajax_reset_all_url = $dg_params['dg_ajax_reset_all_url'];
		$this->a_strings = $dg_params['dg_strings'];
		$this->bs_modal_id = $dg_params['dg_bs_modal_id'];
		$this->bs_modal_content_id = $dg_params['dg_bs_modal_content_id'];
		// sql (6)
		$this->a_columns = $dg_params['dg_columns'];
		$this->select_count_column = $dg_params['dg_select_count_column'];
		$this->select_from_sql = $dg_params['dg_select_from_sql'];
		$this->select_from_sql_more = $dg_params['dg_select_from_sql_more'];
		$this->a_fixed_where = $dg_params['dg_fixed_where'];
		$this->a_fixed_bind_params = $dg_params['dg_fixed_bind_params'];
		// grid (22)
		$this->rows_per_page_options = $dg_params['dg_rows_per_page_options'];
		$this->rows_per_page = $dg_params['dg_rows_per_page'];

		$this->show_columns_switcher = $dg_params['dg_show_columns_switcher'];
		$this->columns_to_display_options = $dg_params['dg_columns_to_display_options'];
		$this->columns_default_icon = $dg_params['dg_columns_default_icon'];
		$this->columns_more_icon = $dg_params['dg_columns_more_icon'];
		$this->columns_to_display = $dg_params['dg_columns_to_display'];

		$this->show_addnew_record = $dg_params['dg_show_addnew_record'];
		$this->addnew_record_url = $dg_params['dg_addnew_record_url'];

		$this->col_sortable_class = $dg_params['dg_col_sortable_class'];
		$this->sort_asc_indicator = $dg_params['dg_sort_asc_indicator'];
		$this->sort_desc_indicator = $dg_params['dg_sort_desc_indicator'];
		$this->advanced_sorting_options = $dg_params['dg_advanced_sorting_options'];
		$this->sort_simple_field = $dg_params['dg_sort_simple_field'];
		$this->sort_simple_order = $dg_params['dg_sort_simple_order'];
		$this->sort_advanced = $dg_params['dg_sort_advanced'];

		$this->page_num = $dg_params['dg_page_num'];

		$this->allow_export_excel = $dg_params['dg_allow_export_excel'];
		$this->export_excel_options = $dg_params['dg_export_excel_options'];
		$this->export_excel_basename = $dg_params['dg_export_excel_basename'];
		$this->export_excel = $dg_params['dg_export_excel'];

		$this->grid_template_path = $dg_params['dg_grid_template_path'];
		// criteria (2)
		$this->a_criteria = $dg_params['dg_criteria'];
		$this->criteria_template_path = $dg_params['dg_criteria_template_path'];

	}

	// getters -----------------------------------------------------------------
	public function getName() {
		return $this->name;
	}

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

	public function getGridTemplatePath() {
		return $this->grid_template_path;
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
				$this->last_error = $ds->getLastError();
				return false;
			} else {
				$db_data = $ds->getData();
			}
		}
		$this->db_data = $db_data;
		$this->db_data_formatted = $db_data;
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
			echo '<th id="' . $item['id'] . '"' . $item['class'] . '>' . $item['sort_indicator'] . $item['header'] . '</th>' . PHP_EOL;
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
					$class = ' class="' . $column['td_class'] . '"';
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
			echo '<tr>' . PHP_EOL;
			foreach($row as $key => $cell) {
				if($key == 0) {
					echo '<th>' . $cell['data'] . '</th>' . PHP_EOL;
				} else {
					echo '<td' . $cell['class'] . '>' . $cell['data'] . '</td>' . PHP_EOL;
				}
			}
			echo '</tr>' . PHP_EOL;
		}

	}


	public function displayRowsPerPage() {
		foreach($this->rows_per_page_options as $item) {
			echo '<option' . ($this->rows_per_page == $item ? ' selected' : '') . ' value="' . $item . '">' . $item . '</option>' . PHP_EOL;
		}
	}

	public function displayAdvancedSorting() {
		foreach($this->getAdvancedSortingOptions() as $key => $item) {
			if($key == 1 && !$this->getSortSimpleField()) {
				continue;
			}
			echo '<option' . ($this->sort_advanced == $key ? ' selected' : '') . ' value="' . $key . '">' . $item['text'] . '</option>' . PHP_EOL;
		}
	}

	/**
	 * @param $criterion
	 * @return string
	 */
	public function displayCriteriaText($criterion) {

		$params = $this->a_criteria[$criterion]['params_html'];

		// get data from params array
		$wrapper_id = $params['wrapper_id'];
		$wrapper_class = $params['wrapper_class'];

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
		$wrapper_class_html = !$wrapper_class ? '' : ' class="' . $wrapper_class . '"';
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
<div id="{$wrapper_id}"{$wrapper_class_html}>

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
		$wrapper_id = $params['wrapper_id'];
		$wrapper_class = $params['wrapper_class'];

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
		$wrapper_class_html = !$wrapper_class ? '' : ' class="' . $wrapper_class . '"';
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
<div id="{$wrapper_id}"{$wrapper_class_html}>

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
		$wrapper_id = $params['wrapper_id'];
		$wrapper_class = $params['wrapper_class'];

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
		$placeholder = $params['placeholder'];
		$input_value = $params['input_value'];

		// some transformations
		$wrapper_class_html = !$wrapper_class ? '' : ' class="' . $wrapper_class . '"';
		$label_id_html = !$label_id ? '' : ' id="' . $label_id . '"';
		$label_class_html = !$label_class ? '' : ' class="' . $label_class . '"';

		$maxlength_html = !$maxlength ? '' : 'maxlength="' . $maxlength . '"';
		$autocomplete_html = $autocomplete ? '' : 'autocomplete="off"';
		$placeholder_html = !$placeholder ? '' : ' placeholder="' . $placeholder . '"';
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
<div id="{$wrapper_id}"{$wrapper_class_html}>

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
		   {$placeholder_html}
		   value="{$input_value}">

</div>
HTML1;

		return $html1;
	}

	/**
	 * @param $criterion
	 * @return string
	 */
	public function displayCriteriaNumber($criterion) {

		$params = $this->a_criteria[$criterion]['params_html'];

		// get data from params array
		$wrapper_id = $params['wrapper_id'];
		$wrapper_class = $params['wrapper_class'];

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
		$placeholder = $params['placeholder'];
		$input_value = $params['input_value'];

		// some transformations
		$wrapper_class_html = !$wrapper_class ? '' : ' class="' . $wrapper_class . '"';
		$label_id_html = !$label_id ? '' : ' id="' . $label_id . '"';
		$label_class_html = !$label_class ? '' : ' class="' . $label_class . '"';

		$maxlength_html = !$maxlength ? '' : 'maxlength="' . $maxlength . '"';
		$autocomplete_html = $autocomplete ? '' : 'autocomplete="off"';
		$placeholder_html = !$placeholder ? '' : ' placeholder="' . $placeholder . '"';
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
<div id="{$wrapper_id}"{$wrapper_class_html}>

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
		   {$placeholder_html}
		   value="{$input_value}">

</div>
HTML1;

		return $html1;
	}


	/**
	 * @param $criterion
	 * @return string
	 */
	public function displayCriteriaAutocomplete($criterion) {

		$params = $this->a_criteria[$criterion]['params_html'];

		// get data from params array
		$wrapper_id = $params['wrapper_id'];
		$wrapper_class = $params['wrapper_class'];

		$autocomplete_wrapper_class = $params['autocomplete_wrapper_class'];
		$autocomplete_group_wrapper_class = $params['autocomplete_group_wrapper_class'];
		$autocomplete_label_id = $params['autocomplete_label_id'];
		$autocomplete_label_class = $params['autocomplete_label_class'];
		$autocomplete_label = $params['autocomplete_label'];
		$autocomplete_id = $params['autocomplete_id'];
		$autocomplete_name = $params['autocomplete_name'];
		$autocomplete_class = $params['autocomplete_class'];
		$autocomplete_style = $params['autocomplete_style'];
		$autocomplete_placeholder = $params['autocomplete_placeholder'];
		$autocomplete_value = htmlspecialchars($params['autocomplete_value']);

		$filter_wrapper_class = $params['filter_wrapper_class'];
		$filter_group_wrapper_class = $params['filter_group_wrapper_class'];
		$filter_label_id = $params['filter_label_id'];
		$filter_label_class = $params['filter_label_class'];
		$filter_label = $params['filter_label'];
		$filter_id = $params['filter_id'];
		$filter_name = $params['filter_name'];
		$filter_class = $params['filter_class'];
		$filter_style = $params['filter_style'];
		$filter_value = htmlspecialchars($params['filter_value']);

		// some transformations
		$wrapper_class_html = !$wrapper_class ? '' : ' class="' . $wrapper_class . '"';
		$autocomplete_label_id_html = !$autocomplete_label_id ? '' : ' id="' . $autocomplete_label_id . '"';
		$autocomplete_label_class_html = !$autocomplete_label_class ? '' : ' class="' . $autocomplete_label_class . '"';
		$autocomplete_style_html = !$autocomplete_style ? '' : ' style="' . $autocomplete_style . '"';
		$autocomplete_placeholder_html = !$autocomplete_placeholder ? '' : ' placeholder="' . $autocomplete_placeholder . '"';
		$filter_label_id_html = !$filter_label_id ? '' : ' id="' . $filter_label_id . '"';
		$filter_label_class_html = !$filter_label_class ? '' : ' class="' . $filter_label_class . '"';
		$filter_style_html = !$filter_style ? '' : ' style="' . $filter_style . '"';

		$html1 = <<<HTML1
<div id="{$wrapper_id}"{$wrapper_class_html}>

	<div class="{$autocomplete_wrapper_class}">
	
		<div class="{$autocomplete_group_wrapper_class}">
			<label{$autocomplete_label_id_html}{$autocomplete_label_class_html} for="{$autocomplete_id}">{$autocomplete_label}</label>
			<input type="text"
				   id="{$autocomplete_id}"
				   name="{$autocomplete_name}"
				   class="{$autocomplete_class}"
				   {$autocomplete_style_html}
				   {$autocomplete_placeholder_html}
				   value="{$autocomplete_value}">
		</div>
	
	</div>
	
	<div class="{$filter_wrapper_class}">
	
		<div class="{$filter_group_wrapper_class}">
			<label{$filter_label_id_html}{$filter_label_class_html} for="{$filter_id}">{$filter_label}</label>
			<input type="text"
				   id="{$filter_id}"
				   name="{$filter_name}"
				   class="{$filter_class}"
				   {$filter_style_html}
				   value="{$filter_value}"
				   readonly="readonly">
		</div>
		
	</div>
	
</div>
HTML1;

		return $html1;
	}


	public function displayCriteriaMultiselectCheckbox($criterion) {

		$params = $this->a_criteria[$criterion]['params_html'];
		$items_html = '';

		// get data from params array
		$wrapper_id = $params['wrapper_id'];
		$wrapper_class = $params['wrapper_class'];

		$fieldset_id = $params['fieldset_id'];
		$fieldset_class = $params['fieldset_class'];

		$legend = $params['legend'];
		$legend_id = $params['legend_id'];
		$legend_class = $params['legend_class'];
		$legend_style = $params['legend_style'];

		$orientation = $params['orientation'];
		$group_name = $params['group_name'];
		$group_value = $params['group_value'];
		$items = $params['items'];

		// some transformations
		$wrapper_class_html = !$wrapper_class ? '' : ' class="' . $wrapper_class . '"';

		$fieldset_id_html = !$fieldset_id ? '' : ' id="' . $fieldset_id . '"';
		$fieldset_class_html = !$fieldset_class ? '' : ' class="' . $fieldset_class . '"';

		$legend_id_html = !$legend_id ? '' : ' id="' . $legend_id . '"';
		$legend_class_html = !$legend_class ? '' : ' class="' . $legend_class . '"';
		$legend_style_html = !$legend_style ? '' : ' style="' . $legend_style . '"';

		foreach($items as $item) {
			$checked = (in_array($item['input_value'], $group_value) ? ' checked' : '');
			if($orientation === 'horizontal') {
				$items_html .= "<label class=\"{$item['label_class']}\">" . PHP_EOL;
				$items_html .= "<input type=\"checkbox\" id=\"{$item['input_id']}\" name=\"{$group_name}\" value=\"{$item['input_value']}\"{$checked}> {$item['label']}" . PHP_EOL;
				$items_html .= "</label>" . PHP_EOL . PHP_EOL;
			} elseif($orientation === 'vertical') {
				$items_html .= "<div class=\"{$item['group_class']}\">" . PHP_EOL;
				$items_html .= "<label>" . PHP_EOL;
				$items_html .= "<input type=\"checkbox\" id=\"{$item['input_id']}\" name=\"{$group_name}\" value=\"{$item['input_value']}\"{$checked}>" . PHP_EOL;
				$items_html .= "{$item['label']}" . PHP_EOL;
				$items_html .= "</label>" . PHP_EOL;
				$items_html .= "</div>" . PHP_EOL . PHP_EOL;
			}
		}

		$html1 = <<<HTML1
<div id="{$wrapper_id}"{$wrapper_class_html}>

<fieldset{$fieldset_id_html}{$fieldset_class_html}>
    <legend{$legend_id_html}{$legend_class_html}{$legend_style_html}>{$legend}</legend>
	{$items_html}
</fieldset>

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
				// always use UTF-8 for strings in PHPExcel (column header may contain html special chars)
				$column_header = html_entity_decode($column['header'],ENT_QUOTES,'UTF-8');
				array_push($a_excel_columns, array(
					'header' => $column_header,
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
		$select = rtrim($select, ', ') . ' ' .
			($this->columns_to_display === C_PHP_BS_GRID_COLUMNS_DEFAULT ? $this->select_from_sql : $this->select_from_sql_more);

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
		$a_whereSQL = $this->a_fixed_where;
		$bind_params = $this->a_fixed_bind_params;

		foreach($this->a_criteria as $criterion) {

			switch($criterion['type']) {

				case 'text':
					switch($criterion['sql_comparison_operator']) {
						case C_PHP_BS_GRID_CRITERIA_TEXT_IGNORE:
							break;
						case C_PHP_BS_GRID_CRITERIA_TEXT_EQUAL:
							array_push($a_whereSQL, $criterion['sql_column'] . ' = ' . C_PHP_BS_GRID_DACAPO_SQL_PLACEHOLDER);
							array_push($bind_params, $criterion['column_value']);
							break;
						case C_PHP_BS_GRID_CRITERIA_TEXT_STARTS_WITH:
							array_push($a_whereSQL, $criterion['sql_column'] . ' LIKE ' . C_PHP_BS_GRID_DACAPO_SQL_PLACEHOLDER);
							array_push($bind_params, $criterion['column_value'] . '%');
							break;
						case C_PHP_BS_GRID_CRITERIA_TEXT_CONTAINS:
							array_push($a_whereSQL, $criterion['sql_column'] . ' LIKE ' . C_PHP_BS_GRID_DACAPO_SQL_PLACEHOLDER);
							array_push($bind_params, '%' . $criterion['column_value'] . '%');
							break;
						case C_PHP_BS_GRID_CRITERIA_TEXT_IS_NULL:
							array_push($a_whereSQL, $criterion['sql_column'] . ' IS NULL');
							break;
					}
					break;

				case 'number':
					switch($criterion['sql_comparison_operator']) {
						case C_PHP_BS_GRID_CRITERIA_NUMBER_IGNORE:
							break;
						case C_PHP_BS_GRID_CRITERIA_NUMBER_EQUAL:
							array_push($a_whereSQL, $criterion['sql_column'] . ' = ' . C_PHP_BS_GRID_DACAPO_SQL_PLACEHOLDER);
							array_push($bind_params, $criterion['column_value']);
							break;
						case C_PHP_BS_GRID_CRITERIA_NUMBER_GREATER_THAN:
							array_push($a_whereSQL, $criterion['sql_column'] . ' > ' . C_PHP_BS_GRID_DACAPO_SQL_PLACEHOLDER);
							array_push($bind_params, $criterion['column_value']);
							break;
						case C_PHP_BS_GRID_CRITERIA_NUMBER_GREATER_THAN_OR_EQUAL_TO:
							array_push($a_whereSQL, $criterion['sql_column'] . ' >= ' . C_PHP_BS_GRID_DACAPO_SQL_PLACEHOLDER);
							array_push($bind_params, $criterion['column_value']);
							break;
						case C_PHP_BS_GRID_CRITERIA_NUMBER_LESS_THAN:
							array_push($a_whereSQL, $criterion['sql_column'] . ' < ' . C_PHP_BS_GRID_DACAPO_SQL_PLACEHOLDER);
							array_push($bind_params, $criterion['column_value']);
							break;
						case C_PHP_BS_GRID_CRITERIA_NUMBER_LESS_THAN_OR_EQUAL_TO:
							array_push($a_whereSQL, $criterion['sql_column'] . ' <= ' . C_PHP_BS_GRID_DACAPO_SQL_PLACEHOLDER);
							array_push($bind_params, $criterion['column_value']);
							break;
						case C_PHP_BS_GRID_CRITERIA_NUMBER_IS_NULL:
							array_push($a_whereSQL, $criterion['sql_column'] . ' IS NULL');
							break;
					}
					break;

				case 'lookup':
					switch($criterion['sql_comparison_operator']) {
						case C_PHP_BS_GRID_CRITERIA_LOOKUP_IGNORE:
							break;
						case C_PHP_BS_GRID_CRITERIA_LOOKUP_EQUAL:
							array_push($a_whereSQL, $criterion['sql_column'] . ' = ' . C_PHP_BS_GRID_DACAPO_SQL_PLACEHOLDER);
							array_push($bind_params, $criterion['column_value']);
							break;
						case C_PHP_BS_GRID_CRITERIA_LOOKUP_IS_NULL:
							array_push($a_whereSQL, $criterion['sql_column'] . ' IS NULL');
							break;
					}
					break;

				case 'autocomplete':
					if($criterion['column_value']) {
						array_push($a_whereSQL, $criterion['sql_column'] . ' = ' . C_PHP_BS_GRID_DACAPO_SQL_PLACEHOLDER);
						array_push($bind_params, $criterion['column_value']);
					}
					break;

				case 'date':
					switch($criterion['sql_comparison_operator']) {
						case C_PHP_BS_GRID_CRITERIA_DATE_IGNORE:
							break;
						case C_PHP_BS_GRID_CRITERIA_DATE_EQUAL:
							array_push($a_whereSQL, $criterion['sql_column'] . ' = ' . C_PHP_BS_GRID_DACAPO_SQL_PLACEHOLDER);
							array_push($bind_params, $criterion['column_value']);
							break;
						case C_PHP_BS_GRID_CRITERIA_DATE_GREATER_THAN:
							array_push($a_whereSQL, $criterion['sql_column'] . ' > ' . C_PHP_BS_GRID_DACAPO_SQL_PLACEHOLDER);
							array_push($bind_params, $criterion['column_value']);
							break;
						case C_PHP_BS_GRID_CRITERIA_DATE_GREATER_THAN_OR_EQUAL_TO:
							array_push($a_whereSQL, $criterion['sql_column'] . ' >= ' . C_PHP_BS_GRID_DACAPO_SQL_PLACEHOLDER);
							array_push($bind_params, $criterion['column_value']);
							break;
						case C_PHP_BS_GRID_CRITERIA_DATE_LESS_THAN:
							array_push($a_whereSQL, $criterion['sql_column'] . ' < ' . C_PHP_BS_GRID_DACAPO_SQL_PLACEHOLDER);
							array_push($bind_params, $criterion['column_value']);
							break;
						case C_PHP_BS_GRID_CRITERIA_DATE_LESS_THAN_OR_EQUAL_TO:
							array_push($a_whereSQL, $criterion['sql_column'] . ' <= ' . C_PHP_BS_GRID_DACAPO_SQL_PLACEHOLDER);
							array_push($bind_params, $criterion['column_value']);
							break;
						case C_PHP_BS_GRID_CRITERIA_DATE_IS_NULL:
							array_push($a_whereSQL, $criterion['sql_column'] . ' IS NULL');
							break;
					}
					break;

				case 'multiselect_checkbox':
					if($criterion['column_value'] && $criterion['column_value'] != $criterion['value_to_ignore']) {
						$count_params = count($criterion['column_value']);
						if($count_params === 1) {
							array_push($a_whereSQL, $criterion['sql_column'] . ' = ' . C_PHP_BS_GRID_DACAPO_SQL_PLACEHOLDER);
							array_push($bind_params, $criterion['column_value'][0]);
						} else {
							$IN_SQL = '(';
							foreach($criterion['column_value'] as $key => $item) {
								$IN_SQL .= C_PHP_BS_GRID_DACAPO_SQL_PLACEHOLDER;
								if($key < $count_params - 1) {
									$IN_SQL .= ',';
								}
								array_push($bind_params, $criterion['column_value'][$key]);
							}
							$IN_SQL .= ')';
							array_push($a_whereSQL, $criterion['sql_column'] . ' IN ' . $IN_SQL);
						}
					}
					break;
			}
		}

		$filters_all = count($a_whereSQL);
		$filters_fixed = count($this->a_fixed_where);

		if($filters_all) {
			$whereSQL = 'WHERE ';
		}
		foreach($a_whereSQL as $key => $filter) {
			$whereSQL .= $filter;
			if($key < $filters_all - 1)
				$whereSQL .= ' AND ';
		}

		$this->where_sql = $whereSQL;
		$this->bind_params = $bind_params;
		$this->filters_applied = $filters_all - $filters_fixed;

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
			$this->last_error = $ds->getLastError();
			return false;
		}
		$rs = $ds->getData();
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

}