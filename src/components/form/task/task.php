<bbn-form :action="root + 'actions/task/insert_update'"
          :source="source.row"
          :data="source.data || {}"
          @success="onSuccess"
>
  <div class="bbn-grid-fields bbn-spadded">
    <label><?=_('Controller')?></label>
    <div class="bbn-flex-width">
      <bbn-input v-model="source.row.file"
                 required
                 maxlength="100"
                 class="bbn-flex-fill bbn-right-sspace"
      ></bbn-input>
      <bbn-button @click="browseCli">{{_('Browse CLI')}}</bbn-button>
    </div>
    <label><?=_('Priority')?></label>
    <div>
      <bbn-numeric v-model="source.row.priority"
                   :min="1"
                   :max="5"
                   required
                   :autosize="true"
      ></bbn-numeric>
    </div>
    <label><?=_('Frequency')?></label>
    <div>
      <bbn-dropdown v-model="source.row.frequency"
                    :source="frequencies"
                    required
      ></bbn-dropdown>
    </div>
    <label><?=_('Timeout')?></label>
    <div>
      <bbn-numeric v-model="source.row.timeout"
                   :min="0"
                   :mmax="86400"
                   required
      ></bbn-numeric>
    </div>
    <label><?=_('Date/time planned for the next execution')?></label>
    <div>
      <bbn-datetimepicker v-model="source.row.next"
                          format="DD-MM-YYYY HH:mm"
                          :min="currentDate"
      ></bbn-datetimepicker>
    </div>
    <label><?=_('Description of the task')?></label>
    <bbn-rte v-model="source.row.description"
    ></bbn-rte>
  </div>
</bbn-form>