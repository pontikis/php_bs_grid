<form id="php_bs_grid_form"
      action="<?php print $dg->getFormAction() ?>"
      method="post">

    <div class="row"
         style="margin-top: 15px; margin-bottom: 5px;">

        <!--  Rows per page  -->
        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"
             style="margin-bottom: 10px;">

            <div class="form-group form-inline"
                 style="margin-bottom: 0">
                <label for="rows-per-page"><?php print $dg->getString('rows_per_page') ?></label>
                <select id="rows_per_page"
                        name="rows_per_page"
                        class="form-control"
                        style="max-width: 100px;">
					<?php $dg->displayRowsPerPage() ?>
                </select>
            </div>

        </div>

        <!--  Column switcher  -->
		<?php if($dg->getTotalRows() > 0) { ?>

            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3"
                 style="margin-bottom: 10px;">

				<?php if($dg->showColumnsSwitcher()) { ?>

                    <button type="button"
                            id="columns_switcher"
                            class="btn btn-default">
                        <span class="<?php print $dg->getColumnsToDisplayIcon() ?>"></span> <?php print $dg->getColumnsToDisplayText() ?>
                    </button>

				<?php } ?>

            </div>

            <!--  Add new record  -->
			<?php if($dg->showAddnewRecord()) { ?>

				<?php //TODO replace resp-align class in Bootstrap 4 (text-xs- etc)  ?>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 resp-align"
                     style="margin-bottom: 10px;">

                    <button id="addnew_record"
                            class="btn btn-success"><?php print $dg->getString('addnew_record') ?></button>

                </div>

			<?php } ?>

		<?php } else { ?>

            <!--  Add new record  -->
			<?php if($dg->showAddnewRecord()) { ?>

                <div class="col-xs-12"
                     style="margin-bottom: 10px;">
                    <button id="addnew_record"
                            class="btn btn-success"><?php print $dg->getString('addnew_record') ?></button>
                </div>

			<?php } ?>

		<?php } ?>

    </div>

    <!--  Datagrid  -->
	<?php if($dg->getTotalRows() > 0) { ?>

        <div class="table-responsive">

            <table class="table table-bordered table-hover">
                <thead>

                <tr>

                    <th>
                        #
                    </th>

					<?php $dg->displayTableHeaders() ?>

                </tr>

                </thead>

                <tbody>

				<?php $dg->displayTableData() ?>

                </tbody>

            </table>

        </div>

	<?php } ?>

    <div class="row alert alert-info"
         style="margin-left: auto; margin-right: auto; padding: 5px;"
         role="alert">

        <!--  Query results info  -->
        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5"
             style="margin-top: 8px; margin-bottom: 10px;">

			<?php if($dg->getTotalRows()) { ?>

                <strong><?php print $dg->getString('total_rows') ?></strong><?php print ': ' . $dg->getTotalRows() . ' ' ?>
                <strong><?php print $dg->getString('Page') . ' ' ?></strong> <?php print $dg->getPageNum() ?>
				<?php print $dg->getString('from') . ' ' ?><?php print $dg->getTotalPages() ?>
                (<strong><?php print $dg->getString('rows') ?></strong><?php print ' ' . $dg->getFirstRowNumInPage() . ' - ' . $dg->getLastRowNumInPage() ?>)

			<?php } else { ?>

				<?php print $dg->getString('no_rows_returned') ?>

			<?php } ?>

        </div>

		<?php if($dg->getTotalRows() > 0) { ?>

            <!--  Advanced sorting  -->
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">

				<?php if($dg->getAdvancedSortingOptions()) { ?>

                    <div class="form-group form-inline"
                         style="margin-bottom: 0">
                        <label for="sort_advanced"><i class="glyphicon glyphicon-sort"></i> <?php print $dg->getString('advanced_sorting') ?>
                        </label>
                        <select id="sort_advanced"
                                name="sort_advanced"
                                class="form-control">
							<?php $dg->displayAdvancedSorting() ?>
                        </select>
                    </div>

				<?php } ?>

            </div>

            <!--  Excel export  -->
            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2"
                 style="margin-bottom: 0; margin-top: 5px;">

				<?php if($dg->allowExportExcel()) { ?>

                    <button type="button"
                            id="export_excel_btn"
                            class="btn btn-sm btn-success">
                        <span class="glyphicon glyphicon-export"></span> <?php print $dg->getString('export_excel') ?>
                    </button>

				<?php } ?>

            </div>

		<?php } ?>

    </div>

    <!--  Pagination  -->
	<?php if($dg->getTotalPages() > 1) { ?>

        <div class="row"
             style="margin-top: 15px; margin-bottom: 5px;">

            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8"
                 style="margin-bottom: 10px;">

                <nav>
                    <ul class="pager"
                        style="margin: 0">

						<?php if($dg->getPageNum() > 1) { ?>

                            <li><a href="javascript:void(0);"
                                   id="go_top"><span class="glyphicon glyphicon-fast-backward"
                                                     aria-hidden="true"></span><span class="visible-lg-inline-block"
                                                                                     style="margin-left: 5px;"><?php print $dg->getString('first_page') ?></span></a>
                            </li>
                            <li><a href="javascript:void(0);"
                                   id="go_back"><span class="glyphicon glyphicon-backward"
                                                      aria-hidden="true"></span><span class="visible-lg-inline-block"
                                                                                      style="margin-left: 5px;"><?php print $dg->getString('previous_page') ?></span></a>
                            </li>

						<?php } ?>

						<?php if($dg->getPageNum() < $dg->getTotalPages()) { ?>

                            <li><a href="javascript:void(0);"
                                   id="go_forward"><span class="visible-lg-inline-block"
                                                         style="margin-right: 5px;"><?php print $dg->getString('next_page') ?></span><span class="glyphicon glyphicon-forward"
                                                                                                                                           aria-hidden="true"></span></a>
                            </li>
                            <li><a href="javascript:void(0);"
                                   id="go_bottom"><span class="visible-lg-inline-block"
                                                        style="margin-right: 5px;"><?php print $dg->getString('last_page') ?></span><span class="glyphicon glyphicon-fast-forward"
                                                                                                                                          aria-hidden="true"></span></a>
                            </li>

						<?php } ?>

                    </ul>
                </nav>

            </div>

            <!--  Go to page  -->
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4"
                 style="margin-bottom: 10px;">

                <div class="input-group">
                        <span class="input-group-btn">
                            <button type="button"
                                    id="go_to_page"
                                    class="btn btn-default"><?php print $dg->getString('go_to_page') ?></button>
                        </span>

                    <input type="text"
                           id="page_num"
                           name="page_num"
                           class="form-control"
                           style="width: 100px;"
                           value="<?php print $dg->getPageNum() ?>">
                </div>

            </div>

        </div>

	<?php } ?>

    <!--  Criteria  -->
	<?php
	if($dg->getCriteriaTemplatePath()) {
		include_once $dg->getCriteriaTemplatePath();
	}
	?>

    <!--  Hidden fields  -->
    <input type="hidden"
           id="total_pages"
           name="total_pages"
           value="<?php print $dg->getTotalPages() ?>">

    <input type="hidden"
           id="columns_to_display"
           name="columns_to_display"
           value="<?php print $dg->getColumnsToDisplay() ?>">

    <input type="hidden"
           id="sort_simple_field"
           name="sort_simple_field"
           value="<?php print $dg->getSortSimpleField() ?>">

    <input type="hidden"
           id="sort_simple_order"
           name="sort_simple_order"
           value="<?php print $dg->getSortSimpleOrder() ?>">

    <input type="hidden"
           id="export_excel"
           name="export_excel"
           value="<?php print $dg->getExportExcel() ?>">

</form>