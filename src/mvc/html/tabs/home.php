<!-- HTML Document -->
<bbn-splitter orientation="vertical" class="appui-cron-main-container">
  <bbn-pane :size="200">
    <div class="bbn-full-screen bbn-middle">
      <div class="bbn-hpadded bbn-block bbn-grid-fields bbn-c">
        <label class="bbn-xl bbn-b bbn-nowrap"
             v-text="_('Background processes are') + ' ' + (source.active ? '' : _('not') + ' ') + _('activated')">
        </label>
        <bbn-switch v-model="source.active"
                    :novalue="false"
                    :value="true"
                    style="margin-right: 2em">
        </bbn-switch>
        <label class="bbn-xl bbn-b bbn-nowrap"
               v-text="_('The application poller process is') + ' ' + (source.poll ? '' : _('not') + ' ') + _('activated')">
        </label>
        <bbn-switch v-model="source.poll"
                    :novalue="false"
                    :value="true"
                    :disabled="!source.active"
                    style="margin-right: 2em">
        </bbn-switch>
        <div class="bbn-grid-full">
          <span v-if="source.pollid" class="bbn-green">
            The poller process is running
          </span>
          <span v-else class="bbn-red">
            The poller process is not running
          </span>
        </div>
        <label class="bbn-xl bbn-b"
             v-text="_('The application CRON task system ') + ' ' + (source.cron ? '' : _('not') + ' ') + _('activated')">
        </label>
        <bbn-switch v-model="source.cron"
                    :novalue="false"
                    :value="true"
                    :disabled="!source.active"
                    style="margin-right: 2em">
        </bbn-switch>
        <div class="bbn-grid-full">
          <span v-if="source.cronid" class="bbn-green">
            The CRON process is running
          </span>
          <span v-else class="bbn-red">
            The CRON process is not running
          </span>
        </div>
      </div>
    </div>
  </bbn-pane>
  <bbn-pane>
    <bbn-splitter orientation="horizontal" :resizable="true">
      <bbn-pane>
        <bbn-splitter orientation="vertical">
          <bbn-pane>
            <ul>
              <li :class="['k-widget', 'bbn-w-100', 'bbn-spadded', source.pollid ? 'bbn-green' : 'bbn-red']">
                <div class="bbn-block">
                  <span class="bbn-large"
                     :title="_('Poller')"
                     v-text="_('Poller')"></span>
                </div>
                <div class="bbn-block bbn-xl" style="float: right">
                  <a :title="_('Started at ') + fdate(source.polltime)" href="javascript:;">
                    <i class="fa fa-clock-o"></i>
                  </a>
                  <a :title="_('ProcessID ') + source.pollid" href="javascript:;">
                    <i class="fa fa-cog"></i>
                  </a>
                  <a :title="_('See log')" href="javascript:;" @click="currentLog = 'poll'">
                    <i class="fa fa-file-text"></i>
                  </a>
                </div>
              </li>
              <li v-for="(t, i) in source.tasks"
                   class="k-widget bbn-w-100 bbn-spadded">
                <div class="bbn-block">
                  <span class="bbn-large"
                     :title="t.description"
                     v-text="t.file"></span>
                </div>
                <div class="bbn-block bbn-xl" style="float: right">
                  <a :title="_('Next: ') + fdate(t.next) + _('\nLast: ') + fdate(t.prev)" href="javascript:;">
                    <i class="fa fa-clock-o"></i>
                  </a>
                  <a :title="_('See log')" href="javascript:;" @click="currentLog = t.id">
                    <i class="fa fa-file-text"></i>
                  </a>
                </div>
              </li>
            </ul>
          </bbn-pane>
          <bbn-pane :size="30" class="bbn-middle">
            <div class="bbn-large bbn-c">
	            <a :href="source.root + 'tabs/list'">Go to full list</a>
            </div>
          </bbn-pane>
        </bbn-splitter>
      </bbn-pane>
      <bbn-pane>
        <bbn-code ref="code"
                  :value="currentCode"
                  :readonly="true"
                  mode="clike">
        </bbn-code>
      </bbn-pane>
    </bbn-splitter>
  </bbn-pane>
</bbn-splitter>