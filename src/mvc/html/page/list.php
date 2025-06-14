<bbn-table :source="source.root + 'page/list'"
           :url="source.root + 'actions/task/insert_update'"
           :sortable="true"
           :pageable="true"
           editable="popup"
           editor="appui-cron-form-task"
           :map="addRunning"
           :trClass="(data) => {if ( data.isRunning ){ return 'bbn-bg-red';}}"
           uid="id"
           ref="table"
           :toolbar="[{
             label: _('New task'),
             action: 'insert',
             icon: 'nf nf-fa-plus'
           }]">
  <bbns-column field="id"
              :invisible="true"
              :editable="false"/>
  <bbns-column field="active"
              :width="90"
              label="<?= _('Active') ?>"
              :component="$options.components['appui-cron-switch']"
              :editable="false"
              cls="bbn-c"/>
  <bbns-column field="file"
              label="<?= _('Controller') ?>"
              :render="renderFile"
              :required="true"
              :min-width="200"/>
  <bbns-column field="priority"
              :width="50"
              label="<?= _('Prio.') ?>"
              flabel="<?= _('Priority') ?>"
              type="number"
              :required="true"
              :default="5"
              cls="bbn-c"/>
  <bbns-column field="frequency"
              :source="frequencies"
              :invisible="true"
              label="<?= _('Frequency') ?>"
              flabel="<?= _('The time to wait between each execution') ?>"
              :required="true"
              :width="150"/>
  <bbns-column field="timeout"
              :invisible="true"
              label="<?= _('Timeout') ?>"
              flabel="<?= _('Timeout in seconds') ?>"
              :required="true"
              type="number"
              :width="80"/>
  <bbns-column field="prev"
              :width="90"
              label="<?= _('Prev') ?>"
              flabel="<?= _('Date/time of the previous execution') ?>"
              type="datetime"
              :editable="false"
              cls="bbn-c"/>
  <bbns-column field="next"
              :width="90"
              label="<?= _('Next') ?>"
              flabel="<?= _('Date/time planned for the next execution') ?>"
              type="datetime"
              cls="bbn-c"/>
  <!--bbns-column field="duration"
              :width="60"
              label="<?= _('Dur.') ?>"
              flabel="<?= _('Average duration of the execution') ?>"
              :render="renderAvgDuration"
              :editable="false"
              cls="bbn-c"
  ></bbns-column-->
  <bbns-column field="pid"
              :width="70"
              type="number"
              label="<?= _('PID') ?>"
              flabel="<?= _('Process ID') ?>"
              :editable="false"
              cls="bbn-c"/>
  <bbns-column field="num"
              :width="70"
              type="number"
              label="<?= _('Num') ?>"
              flabel="<?= _('Total number of executions') ?>"
              :editable="false"
              cls="bbn-c"/>
  <bbns-column field="description"
              label="<?= _('Description') ?>"
              flabel="<?= _('Description of the task') ?>"
              :min-width="200"
              :invisible="true"/>
  <bbns-column v-if="source.is_dev"
              :width="170"
              flabel="<?= _('Actions') ?>"
              :buttons="renderButtons"
              cls="bbn-r"/>
</bbn-table>
