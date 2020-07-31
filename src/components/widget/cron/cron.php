<div class="bbn-padded">
  <div class="bbn-w-100">
    <bbn-table :source="source.cron ? source.cron.data : []"
              :scrollable="false"
    >
      <bbns-column title="<?=_('Description')?>"
                  field="description"
                  :render="renderDescription"
      ></bbns-column>
      <bbns-column title="<?=_('Next')?>"
                  field="next"
                  :component="$options.components.next"
                  cls="bbn-c"
                  width="150"
      ></bbns-column>
    </bbn-table>
  </div>
</div>