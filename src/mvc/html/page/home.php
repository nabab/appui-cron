<!-- HTML Document -->
<div class="bbn-overlay appui-cron-main-container">
  <div class="bbn-flex-height">
    <bbn-toolbar>
      <bbn-button title="<?=_('Refresh list')?>"
                  icon="nf nf-fa-refresh"
                  @click="refreshTasks"
                  class="bbn-button-icon-only"
                  ></bbn-button>
      
      <div class="bbn-toolbar-separator"></div>
      
      <bbn-button title="<?=_('Go to full list')?>"
                  icon="nf nf-fa-th_list"
                  :url="source.root + 'page/list'"
                  class="bbn-button-icon-only"
                  ></bbn-button>
      
      <div class="bbn-toolbar-separator"></div>
      
      <div class="bbn-nowrap bbn-p" @click="toggleActive" @mouseenter="mouseOver" @mouseleave="mouseOut">
        <div :class="{
                     'bbn-diode': true,
                     'bbn-color-blue': source.active,
                     'bbn-on': source.active,
                     'bbn-color-red': !source.active,
                     }" style="width: 24px; height: 24px"></div>
        <div class="bbn-b bbn-large bbn-iblock bbn-hxspadded" v-text="source.active ? _('ON') : _('OFF')"></div>
      </div>
      
      <div class="bbn-toolbar-separator"></div>
      
      <div class="bbn-nowrap bbn-p" @click="togglePoll" @mouseenter="mouseOver" @mouseleave="mouseOut">
        <div :class="{
                     'bbn-diode': true,
                     'bbn-color-green': source.active && source.poll,
                     'bbn-on': source.active,
                     'bbn-color-orange': !source.active && source.poll,
                     'bbn-color-red': !source.poll,
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
                     'bbn-color-green': source.active && source.cron,
                     'bbn-on': source.active,
                     'bbn-color-orange': !source.active && source.cron,
                     'bbn-color-red': !source.cron,
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
      <bbn-splitter orientation="horizontal"
                    class="appui-cron-main-container"
                    :resizable="true"
                    :collapsible="true"
      >
        <bbn-pane :size="250" class="appui-cron-pane-list bbn-bordered">
          <h3 v-text="_('Running tasks')" class="bbn-left-space"></h3>
          <bbn-list :source="tasksList"
                    :component="$options.components['activeTasksItem']"
                    @select="select1"
                    ref="list1"
                    source-value="id"
                    uid="file"
                    ></bbn-list>
          <h3 v-text="_('Coming tasks')" class="bbn-left-space"></h3>
          <bbn-list :source="source.tasks"
                    :component="$options.components['tasksItem']"
                    @select="select2"
                    ref="list2"
                    source-value="id"
                    uid="file"
                    ></bbn-list>
        </bbn-pane>
        <bbn-pane :size="280" class="bbn-bordered">
          <div class="bbn-flex-height">
            <div class="bbn-c">
              <bbn-dropdown :source="source.quicklist" v-model="currentID" :placeholder="_('Pick a task')"></bbn-dropdown>
            </div>
            <div class="bbn-flex-fill">
              <bbn-tree v-if="treeVisible"
                        :map="treeMapper"
                        @select="selectTree"
                        :source="source.root + 'actions/task/history/' + currentID"
                        :hybrid="true"
              ></bbn-tree>
              <h3 class="bbn-c" v-else v-text="_('Select a task')"></h3>
            </div>
          </div>
        </bbn-pane>
        <bbn-pane>
          <div class="bbn-flex-height">
            <div v-if="currentLog"
                 class="bbn-header bbn-w-100 bbn-spadded"
                 >
              <div class="bbn-flex-width bbn-hpadded bbn-vmiddle bbn-w-100"
                   style="display: flex"
                   >
                <div>
                  <bbn-button icon="nf nf-fa-angle_left"
                              title="<?=_('Prev')?>"
                              @click="changeFile('prev')"
                              class="bbn-button-icon-only"
                              ></bbn-button>
                  <bbn-button icon="nf nf-fa-angle_right"
                              title="<?=_('Next')?>"
                              @click="changeFile('next')"
                              class="bbn-button-icon-only"
                              ></bbn-button>
                </div>
                <div class="bbn-flex-fill bbn-c">
                  <span v-text="currentFile"></span>
                </div>
                <div class="bbn-hmargin">
                  <bbn-button icon="nf nf-fa-trash"
                              title="<?=_('Delete log file')?>"
                              @click="deleteLog"
                              class="bbn-button-icon-only"
                              ></bbn-button>
                  <bbn-button icon="nf nf-fa-trash"
                              title="<?=_('Delete all log files')?>"
                              @click="deleteAllLog"
                              class="bbn-button-icon-only bbn-red"
                              ></bbn-button>
                </div>

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
            <bbn-code ref="code"
                      :value="currentCode"
                      :readonly="true"
                      mode="clike"
                      class="bbn-flex-fill"
                      ></bbn-code>
          </div>
        </bbn-pane>
      </bbn-splitter>
    </div>
  </div>
</div>