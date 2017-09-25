<bbn-table :source="source.root + 'list'"
           :expander="$options.components['appui-cron-history']"
           :sortable="true"
           :pageable="true"
           editable="popup"
           :map="addRunning"
           :trClass="(data) => {if ( data.isRunning ){ return 'bbn-bg-red';}}"
           uid="id"
           ref="table"
           :toolbar="[{
             text: _('New task'),
             command: 'insert',
             icon: 'fa fa-new'
           }]"
>
  <bbn-column field="id"
              :hidden="true"
              :editable="false"
  ></bbn-column>
  <bbn-column field="file"
              :width="150"
              title="<?=_('Controller')?>"
              :render="renderFile"
              :required="true"
  ></bbn-column>
  <bbn-column field="priority"
              :width="50"
              title="<?=_('Prio.')?>"
              ftitle="<?=_('Priority')?>"
              type="number"
              :required="true"
              :default="5"
              :options="{min: 1, max: 5}"
  ></bbn-column>
  <bbn-column field="prev"
              :width="90"
              title="<?=_('Prev')?>"
              ftitle="<?=_('Date/time of the previous execution')?>"
              type="date"
              :editable="false"
  ></bbn-column>
  <bbn-column field="next"
              :width="90"
              title="<?=_('Next')?>"
              ftitle="<?=_('Date/time planned for the next execution')?>"
              type="date"
              :render="renderNext"
  ></bbn-column>
  <bbn-column field="duration"
              :width="60"
              title="<?=_('Dur.')?>"
              ftitle="<?=_('Average duration of the execution')?>"
              :render="renderAvgDuration"
              :editable="false"
  ></bbn-column>
  <bbn-column field="num"
              :width="70"
              type="number"
              title="<?=_('Num')?>"
              ftitle="<?=_('Total number of executions')?>"
              :editable="false"
  ></bbn-column>
  <bbn-column field="description"
              title="<?=_('Description')?>"
              ftitle="<?=_('Description of the task')?>"
              editor="bbn-rte"
  ></bbn-column>
  <bbn-column v-if="source.is_dev"
              :width="110"
              title="<?=_('Actions')?>"
              :buttons="renderButtons"
              fixed="right"
  ></bbn-column>
  
</bbn-table>
<script id="tpl-form_cron" type="text/x-kendo-template">
  <input type="hidden" name="id">
  <input type="hidden" name="project">

  <label for="dscawerejio98yI00" class="bbn-form-label">
    Controller
  </label>
  <div class="bbn-form-field">
    <input class="k-textbox" id="dscawerejio98yI00" name="file" required maxlength="100">&nbsp;
    <button class="k-button">Browse CLI</button>
  </div>

  <label for="dscawerejio98yI01" class="bbn-form-label">
    Description
  </label>
  <div class="bbn-form-field">
    <input type="text" class="k-textbox" id="dscawerejio98yI01" name="description" required style="min-width: 300px" maxlength="255">
  </div>

  <label for="dscawerejio98yI02" class="bbn-form-label">
    Frequency
  </label>
  <div class="bbn-form-field">
    <select name="frequency" id="dscawerejio98yI02" required data-role="dropdownlist" data-option-label="Choose" style="width: 300px">
      <option value="i1">Every minute</option>
      <option value="i2">Every 2 minutes</option>
      <option value="i5">Every 5 minutes</option>
      <option value="i10">Every 10 minutes</option>
      <option value="i15">Every 15 minutes</option>
      <option value="i20">Every 20 minutes</option>
      <option value="i30">Every 30 minutes</option>
      <option value="i45">Every 45 minutes</option>
      <option value="h1">Every hour</option>
      <option value="h2">Every 2 hours</option>
      <option value="h4">Every 4 hours</option>
      <option value="h8">Every 8 hours</option>
      <option value="h12">Every 12 hours</option>
      <option value="d1">Every day</option>
      <option value="d2">Every 2 days</option>
      <option value="d3">Every 3 days</option>
      <option value="w1">Every week</option>
      <option value="w2">Every 2 weeks</option>
      <option value="m1">Every month</option>
      <option value="m2">Every 2 month</option>
      <option value="m3">Every 3 month</option>
      <option value="m6">Every 6 month</option>
      <option value="y1">Every year</option>
    </select>
  </div>

  <label for="dscawerejio98yI05" class="bbn-form-label">
    Next execution
  </label>
  <div class="bbn-form-field">
    <input type="datetime" name="next" id="dscawerejio98yI05" required data-role="datetimepicker" data-format="F" style="width: 300px">
  </div>

  <label for="dscawerejio98yI03" class="bbn-form-label">
    Timeout in seconds
  </label>
  <div class="bbn-form-field">
    <input type="number" id="dscawerejio98yI03" name="timeout" required="required" data-role="numerictextbox" data-format="n0" data-min="0" data-max="86400" style="width: 300px">
  </div>

  <label for="dscawerejio98yI06" class="bbn-form-label">
    Priority
  </label>
  <div class="bbn-form-field">
    <input type="number" id="dscawerejio98yI06" name="priority" required="required" data-role="numerictextbox" data-format="n0" data-min="1" data-max="5" style="width: 300px">
  </div>
</script>
