<div class="bbn-w-100 bbn-padding">
  <div class="bbn-w-100 bbn-rel">
    <bbn-table v-if="source.cron"
    :source="source.cron ? source.cron.data : []"
              :scrollable="false"
    >
      <bbns-column label="<?= _('Description') ?>"
                  field="description"
                  :render="renderDescription"
      ></bbns-column>
      <bbns-column label="<?= _('Next') ?>"
                  field="next"
                  :component="$options.components.next"
                  cls="bbn-c"
                  width="150"
      ></bbns-column>
    </bbn-table>
  </div>
</div>