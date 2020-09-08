<div class="appui-cron-task bbn-overlay">
  <bbn-splitter orientation="horizontal"
                class="appui-cron-main-container"
                :resizable="true"
                :collapsible="true"
  >
    <bbn-pane :size="280" class="bbn-bordered">
      <div class="bbn-overlay">
        <bbn-tree v-if="treeVisible"
                  :map="treeMapper"
                  @select="selectTree"
                  :source="root + 'actions/task/history/' + currentID"
                  uid="fpath"
                  :path="currentTreePath"
        ></bbn-tree>
        <h3 class="bbn-c" v-else v-text="_('Select a task')"></h3>
      </div>
    </bbn-pane>
    <bbn-pane>
      <div class="bbn-flex-height">
        <div v-if="currentLog"
             class="bbn-flex"
        >
          <div class="bbn-w-50">
            <bbn-toolbar class="bbn-spadded">
              <bbn-button icon="nf nf-fa-angle_left"
                          title="<?=_('Prev')?>"
                          @click="changeFile('prev')"
                          :notext="true"
              ></bbn-button>
              <bbn-button icon="nf nf-fa-angle_right"
                          title="<?=_('Next')?>"
                          @click="changeFile('next')"
                          :notext="true"
                          class="bbn-left-sspace"
              ></bbn-button>
              <div class="bbn-toolbar-separator bbn-no-vmargin"></div>
              <bbn-button icon="nf nf-fa-trash"
                          title="<?=_('Delete log file')?>"
                          @click="deleteLog"
                          :notext="true"
              ></bbn-button>
              <div class="bbn-toolbar-separator bbn-no-vmargin"></div>
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
            </bbn-toolbar>
            <div class="bbn-padded bbn-grid-fields">
              <label><?=_('File')?></label>
              <div v-text="currentFile"></div>
              <label><?=_('Executed from')?></label>
              <div>xxxxxxx</div>
              <label><?=_('Start time')?></label>
              <div>xxxxxxx</div>
              <label><?=_('Execution time')?></label>
              <div>xxxxxxx</div>
            </div>
          </div>
          <div class="bbn-w-50 bbn-bordered-left">
            <bbn-toolbar class="bbn-spadded bbn-no-border-left">
              <bbn-button icon="nf nf-fa-edit"
                          title="<?=_('Edit')?>"
                          :notext="true"
              ></bbn-button>
              <bbn-button icon="nf nf-fa-play"
                          title="<?=_('Run')?>"
                          :notext="true"
                          class="bbn-left-sspace"
                          :disabled="!!source.pid"
              ></bbn-button>
              <div class="bbn-toolbar-separator bbn-no-vmargin"></div>
              <bbn-button icon="nf nf-fa-trash"
                          title="<?=_('Delete all log files')?>"
                          @click="deleteAllLog"
                          :notext="true"
                          class="bbn-red"
              ></bbn-button>
            </bbn-toolbar>
            <div class="bbn-padded bbn-grid-fields">
              <label><?=_('File')?></label>
              <div v-text="source.file"></div>
              <label><?=_('Description')?></label>
              <div v-text="source.description"></div>
              <label><?=_('Priority')?></label>
              <div v-text="source.priority"></div>
              <label><?=_('Frequency')?></label>
              <div v-text="currentFrequency"></div>
              <label><?=_('Timeout')?></label>
              <div v-text="source.cfg.timeout"></div>
              <label><?=_('PID')?></label>
              <div class="bbn-b" v-text="source.pid"></div>
            </div>
          </div>
        </div>
        <div v-if="currentCode"
             class="bbn-flex-fill bbn-flex-height"
        >
          <div class="bbn-header bbn-spadded bbn-c"><?=_('OUTPUT')?></div>
          <bbn-code ref="code"
                    :value="currentCode"
                    :readonly="true"
                    mode="clike"
                    class="bbn-flex-fill"
          ></bbn-code>
        </div>
      </div>
    </bbn-pane>
  </bbn-splitter>
</div>