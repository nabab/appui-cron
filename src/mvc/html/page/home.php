<!-- HTML Document -->
<div class="bbn-overlay appui-cron-main-container">
  <div class="bbn-flex-height">
    <bbn-toolbar>
      <bbn-button title="<?= _('Refresh list') ?>"
                  icon="nf nf-fa-refresh"
                  @click="refreshTasks"
                  class="bbn-button-icon-only"
                  ></bbn-button>

      <div class="bbn-toolbar-separator"></div>

      <bbn-button title="<?= _('Go to full list') ?>"
                  icon="nf nf-fa-th_list"
                  :url="source.root + 'page/list'"
                  class="bbn-button-icon-only"
                  ></bbn-button>

      <div class="bbn-toolbar-separator"></div>

      <div class="bbn-nowrap bbn-p" @click="toggleActive" @mouseenter="mouseOver" @mouseleave="mouseOut">
        <div :class="{
                     'bbn-diode': true,
                     'bbn-bg-blue': source.active,
                     'bbn-on': source.active,
                     'bbn-bg-red': !source.active,
                     }" style="width: 24px; height: 24px"></div>
        <div class="bbn-b bbn-large bbn-iblock bbn-hxspadded" v-text="source.active ? _('ON') : _('OFF')"></div>
      </div>

      <div class="bbn-toolbar-separator"></div>

      <div class="bbn-nowrap bbn-p" @click="togglePoll" @mouseenter="mouseOver" @mouseleave="mouseOut">
        <div :class="{
                     'bbn-diode': true,
                     'bbn-bg-green': source.active && source.poll,
                     'bbn-on': source.active,
                     'bbn-bg-orange': !source.active && source.poll,
                     'bbn-bg-red': !source.poll,
                     }"></div>
        <div class="bbn-iblock bbn-hxspadded">
          <span v-text="_('Poller')" ></span><br>
          <span v-if="source.pollid" class="bbn-iblock">
            <span v-text="_('PID')"></span>
            <span class="bbn-purple bbn-b" v-text="source.pollid"></span>
          </span>
          <span v-else class="bbn-b" v-text="_('No process')"></span>
        </div>
      </div>

      <div class="bbn-toolbar-separator"></div>

      <div class="bbn-nowrap bbn-p" @click="toggleCron" @mouseenter="mouseOver" @mouseleave="mouseOut">
        <div :class="{
                     'bbn-diode': true,
                     'bbn-bg-green': source.active && source.cron,
                     'bbn-on': source.active,
                     'bbn-bg-orange': !source.active && source.cron,
                     'bbn-bg-red': !source.cron,
                     }"></div>
        <div class="bbn-iblock bbn-hxspadded">
          <span class="bbn-iblock" v-text="_('Tasks')"></span><br>
          <span v-if="source.cronid" class="bbn-iblock">
            <span v-text="_('PID')"></span>
            <span class="bbn-purple bbn-b" v-text="source.cronid"></span>
          </span>
          <span v-else class="bbn-b" v-text="_('No process')"></span>
        </div>

      </div>
    </bbn-toolbar>
    <div class="bbn-flex-fill">
      <div class="bbn-overlay">
        <bbn-splitter orientation="horizontal"
                      class="appui-cron-main-container"
                      :resizable="true"
                      :collapsible="true"
        >
          <bbn-pane :size="250"
                    class="appui-cron-pane-list"
                    :scrollable="true"
          >
            <div class="bbn-w-100 bbn-bottom-space">
              <div v-text="_('Current task')"
                  class="bbn-c bbn-header bbn-b bbn-large bbn-no-border-top bbn-no-border-right"
              ></div>
              <div class="bbn-w-100 bbn-c bbn-spadded">
                <bbn-dropdown :source="source.quicklist"
                              v-model="currentTask"
                              :placeholder="_('Pick a task')"
                              @change="selectByDD"
                              class="bbn-w-100"
                ></bbn-dropdown>
              </div>
            </div>
            <div class="bbn-w-100">
              <div v-text="_('Running tasks')"
                  class="bbn-b bbn-c bbn-header bbn-large bbn-no-border-right"
              ></div>
              <bbn-list :source="tasksList"
                        :component="$options.components['activeTasksItem']"
                        @select="select1"
                        ref="list1"
                        source-value="id"
                        uid="file"
                        class="bbn-vmargin"
              ></bbn-list>
            </div>
            <div class="bbn-w-100">
              <div v-text="_('Failed tasks')"
                  class="bbn-b bbn-large bbn-c bbn-header bbn-no-border-right"
              ></div>
              <bbn-list :source="source.failed"
                        :component="$options.components['failedItem']"
                        ref="list3"
                        source-value="id"
                        source-text="file"
                        uid="file"
                        @select="select3"
                        class="bbn-vmargin"
              ></bbn-list>
            </div>
            <div class="bbn-w-100">
              <div v-text="_('Coming tasks')"
                  class="bbn-c bbn-header bbn-b bbn-large bbn-no-border-right"
              ></div>
              <bbn-list :source="source.tasks"
                        :component="$options.components['tasksItem']"
                        @select="select2"
                        ref="list2"
                        source-value="id"
                        uid="file"
                        class="bbn-vmargin"
              ></bbn-list>
            </div>
          </bbn-pane>
          <bbn-pane>
            <appui-cron-task v-if="currentTask && showTask"
                            :source="taskSource"
            ></appui-cron-task>
            <div v-else
                class="bbn-overlay bbn-middle"
            >
              <span class="bbn-xl bbn-b"><i class="nf nf-fa-arrow_left bbn-right-space"></i><?= _('Select a task') ?></span>
            </div>
          </bbn-pane>
        </bbn-splitter>
      </div>
    </div>
  </div>
</div>