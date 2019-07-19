<!-- HTML Document -->
<bbn-splitter orientation="horizontal"
              class="appui-cron-main-container"
>
  <bbn-pane size="35%">
    <bbn-toolbar size="60px">
      <div class="bbn-hspadded">
        <bbn-button title="<?=_('Refresh list')?>"
                    icon="nf nf-fa-refresh"
                    @click="updateTasks"
        ></bbn-button>
      </div>
      <div class="bbn-toolbar-separator"></div>
      <div class="bbn-hspadded">
        <bbn-button title="<?=_('Go to full list')?>"
                    icon="nf nf-fa-th_list"
                    :url="source.root + 'page/list'"
        ></bbn-button>
      </div>
    </bbn-toolbar>
    <bbn-list :source="tasksList"
              :component="$options.components['tasksItem']"
    ></bbn-list>
  </bbn-pane>
  <bbn-pane>
    <bbn-splitter orientation="vertical">
      <bbn-pane :size="150">
        <div class="bbn-overlay bbn-middle">
          <div class="bbn-hpadded bbn-block bbn-grid-fields bbn-c">
            <label class="bbn-xl bbn-light bbn-nowrap"
                   v-text="_('Background processes are') + ' ' + (source.active ? '' : _('not') + ' ') + _('activated')">
            </label>
            <bbn-switch v-model="source.active"
                        :novalue="false"
                        :value="true"
                        style="margin-right: 2em">
            </bbn-switch>
            <label :class="['bbn-xl', 'bbn-light', 'bbn-nowrap', {
                     'bbn-green': !!source.poll && source.pollid,
                     'bbn-red': !!source.poll && !source.pollid
                   }]"
                   v-text="_('The application poller process is') + ' ' + (source.poll ? '' : _('not') + ' ') + _('activated')">
                   :title="!!source.poll && source.pollid ? '<?=\bbn\str::escape_squotes(_('The poller process is running'))?>' : (!!source.poll && !source.pollid ? '<?=\bbn\str::escape_squotes(_('The poller process is not running'))?>' : '')"
            </label>
            <bbn-switch v-model="source.poll"
                        :novalue="false"
                        :value="true"
                        :disabled="!source.active"
                        style="margin-right: 2em">
            </bbn-switch>
            <label :class="['bbn-xl', 'bbn-light', {
                     'bbn-green': !!source.cron && source.cronid,
                     'bbn-red': !!source.cron && !source.cronid
                   }]"
                   v-text="_('The application CRON task system ') + ' ' + (source.cron ? '' : _('not') + ' ') + _('activated')"
                   :title="!!source.cron && source.cronid ? '<?=\bbn\str::escape_squotes(_('The CRON process is running'))?>' : (!!source.cron && !source.cronid ? '<?=\bbn\str::escape_squotes(_('The CRON process is not running'))?>' : '')"
            ></label>
            <bbn-switch v-model="source.cron"
                        :novalue="false"
                        :value="true"
                        :disabled="!source.active"
                        style="margin-right: 2em"
            >
            </bbn-switch>
          </div>
        </div>
      </bbn-pane>
      <bbn-pane>
        <div class="bbn-flex-height">
          <div v-if="currentLog"
               class="bbn-widget k-toolbar bbn-w-100"
          >
            <div class="bbn-flex-width bbn-hpadded bbn-vmiddle bbn-w-100"
                 style="display: flex"
            >
              <div>
                <bbn-button icon="nf nf-fa-angle_left"
                            title="<?=_('Prev')?>"
                            @click="changeFile('prev')"
                ></bbn-button>
                <bbn-button icon="nf nf-fa-angle_right"
                            title="<?=_('Next')?>"
                            @click="changeFile('next')"
                ></bbn-button>
              </div>
              <div class="bbn-flex-fill bbn-c">
                <span v-text="currentFile"></span>
              </div>
              <div class="bbn-hmargin">
                <bbn-button icon="nf nf-fa-trash"
                            title="<?=_('Delete log file')?>"
                            @click="deleteLog"
                ></bbn-button>
                <bbn-button icon="nf nf-fa-trash"
                            title="<?=_('Delete all log files')?>"
                            @click="deleteAllLog"
                            style="color: red"
                ></bbn-button>
              </div>
              <div>
                <bbn-switch :value="true"
                            :novalue="false"
                            on-icon="nf nf-fa-refresh"
                            off-icon="nf nf-fa-refresh"
                            :no-icon="false"
                            @change="toggleAutoLog"
                            :checked="!!autoLog"
                            class="appui-cron-autorefresh"
                            title="<?=_('Auto-refresh')?>"
                ></bbn-switch>
              </div>
            </div>
          </div>
          <bbn-code ref="code"
                    :value="currentCode"
                    :readonly="true"
                    mode="clike"
                    class="bbn-flex-fill"
          ></bbn-code>
        </div>
      </bbn-pane>
    </bbn-splitter>
  </bbn-pane>
</bbn-splitter>
