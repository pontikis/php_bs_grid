<div class="row panel-group"
     style="margin-left: auto; margin-right: auto;"
     id="criteria">

    <div class="panel panel-default">

        <div class="panel-heading">

            <h4 class="panel-title">
                <a data-toggle="collapse"
                   data-parent="#criteria"
                   href="#collapseOne"><i class="glyphicon glyphicon-filter"></i> <?php print $dg->getString('criteria') . ($dg->getFiltersAppliedCount() ? ' (' . $dg->getFiltersAppliedCount() . ')' : '') ?>
                </a>
            </h4>

        </div>

        <div id="collapseOne"
             class="panel-collapse collapse<?php print ($dg->getFiltersAppliedCount() ? ' in' : '') ?>">

            <div class="panel-body">

                <div class="row"
                     style="margin-top: 15px; margin-bottom: 5px;">

                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"
                         style="margin-bottom: 10px;">

						<?php print $dg->displayCriteriaText('lastname') ?>

                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"
                         style="margin-bottom: 10px;">

						<?php print $dg->displayCriteriaText('firstname') ?>

                    </div>

                </div>

                <div class="row"
                     style="margin-top: 15px; margin-bottom: 5px;">

                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"
                         style="margin-bottom: 10px;">

						<?php print $dg->displayCriteriaLookup('gender') ?>

                    </div>

                </div>

                <div class="row"
                     style="margin-top: 15px; margin-bottom: 5px;">

                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"
                         style="margin-bottom: 10px;">

						<?php print $dg->displayCriteriaDate('date_start') ?>

                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"
                         style="margin-bottom: 10px;">

						<?php print $dg->displayCriteriaDate('date_end') ?>

                    </div>

                </div>

                <div id="criteria_tools"
                     class="row"
                     style="margin-top: 15px; margin-bottom: 5px;">

                    <div class="col-xs-12"
                         style="margin-bottom: 10px;">

                        <button type="button"
                                id="criteria_apply"
                                class="btn btn-default"><?php print $dg->getString('apply_criteria') ?></button>

                        <button type="button"
                                id="criteria_reset"
                                class="btn btn-default"><?php print $dg->getString('clear_criteria') ?></button>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>