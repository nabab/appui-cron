<div class="appui-cron-task bbn-overlay">
  <bbn-splitter orientation="horizontal"
                class="appui-cron-main-container"
                :resizable="true"
                :collapsible="true"
  >
    <bbn-pane size="16em">
      <bbn-splitter>
        <bbn-pane :size="200">
          <bbn-calendar :selection="true"
                        :auto-selection="true"
                        start-format="YYYY-MM-DD"
                        end-format="YYYY-MM-DD"
                        v-model="currentDay"
                        :max="today"
                        :title-icon="false"
                        :data="{id: currentID}"
                        :source="root + 'data/task/calendar'"
                        :disable-no-events="true"
                        :event-icon="false"
                        ref="calendar"
          ></bbn-calendar>
        </bbn-pane>
        <bbn-pane>
          <div class="bbn-flex-height">
            <div class="bbn-header bbn-xspadded">
              <div class="bbn-flex-width bbn-vmiddle bbn-bottom-xsspace">
                <span class="bbn-flex-fill bbn-c bbn-b"><?=_('PROCESSES')?></span>
                <i class="nf nf-fa-refresh bbn-p" @click="getRef('list').updateData()"></i>
              </div>
              <div class="bbn-flex-width bbn-vmiddle">
                <i class="nf nf-fa-filter bbn-right-sspace"></i>
                <bbn-dropdown class="bbn-flex-fill"
                              :source="listFilters"
                              v-model="currentListFilter"
                              :nullable="true"
                              placeholder="<?=_('Filter by...')?>"
                ></bbn-dropdown>
              </div>
            </div>
            <div class="bbn-flex-fill">
              <bbn-scroll>
                <bbn-list :source="root + 'data/task/history/' + currentID"
                          :data="{day: currentDay}"
                          @select="selectLog"
                          :component="$options.components.listItem"
                          source-value="start"
                          ref="list"
                          :autobind="false"
                          @hook:mounted="listMounted"
                          :filterable="true"
                          :server-filtering="false"
                          @datareceived="onListDataReceived"
                          @dataloaded="onListDataloaded"
                          :alternate-background="true"
                ></bbn-list>
              </bbn-scroll>
            </div>
          </div>
        </bbn-pane>
      </bbn-splitter>
    </bbn-pane>
    <bbn-pane>
      <div class="bbn-flex-height">
        <div class="bbn-flex">
          <div class="bbn-w-50">
            <div class="bbn-header bbn-spadded bbn-b bbn-c"><?=_('SELECTED PROCESS')?></div>
            <bbn-toolbar class="bbn-spadded">
              <bbn-button icon="nf nf-fa-angle_left"
                          title="<?=_('Prev')?>"
                          @click="prevLog"
                          :notext="true"
                          :disabled="prevButtonDisabled"
              ></bbn-button>
              <bbn-button icon="nf nf-fa-angle_right"
                          title="<?=_('Next')?>"
                          @click="nextLog"
                          :notext="true"
                          class="bbn-left-sspace"
                          :disabled="nextButtonDisabled"
              ></bbn-button>
              <bbn-switch :value="true"
                          :novalue="false"
                          on-icon="nf nf-fa-refresh"
                          off-icon="nf nf-fa-refresh"
                          :no-icon="false"
                          @change="toggleLive"
                          :checked="!!liveOutput"
                          title="<?=_('Live output')?>"
                          :disabled="!currentOutput"
                          class="bbn-left-sspace"
              ></bbn-switch>
            </bbn-toolbar>
            <div class="bbn-padded bbn-grid-fields">
              <label><?=_('Process ID')?></label>
              <div v-text="currentObj.pid"></div>
              <label><?=_('CRON ID')?></label>
              <div v-text="currentID"></div>
              <label><?=_('Start')?></label>
              <div v-text="currentObj.startFormatted || ''"></div>
              <label><?=_('End')?></label>
              <div v-text="currentObj.endFormatted || ''"></div>
              <label><?=_('Duration')?></label>
              <div v-text="currentObj.duration || ''"></div>
            </div>
          </div>
          <div class="bbn-w-50 bbn-bordered-left">
          <div class="bbn-header bbn-spadded bbn-b bbn-c"><?=_('TASK INFO')?></div>
            <bbn-toolbar class="bbn-spadded bbn-no-border-left">
              <bbn-button icon="nf nf-fa-refresh"
                          title="<?=_('Refresh')?>"
                          :notext="true"
                          @click="refresh"
              ></bbn-button>
              <bbn-button icon="nf nf-fa-edit"
                          title="<?=_('Edit')?>"
                          :notext="true"
                          class="bbn-left-sspace"
                          @click="edit"
              ></bbn-button>
              <bbn-button icon="nf nf-fa-play"
                          title="<?=_('Run')?>"
                          :notext="true"
                          class="bbn-left-sspace"
                          v-if="!currentTaskObj.pid"
                          @click="runTask"
              ></bbn-button>
              <bbn-button icon="nf nf-oct-stop"
                          title="<?=_('Reset')?>"
                          :notext="true"
                          class="bbn-left-sspace bbn-red"
                          v-else-if="currentTaskObj.failed"
                          @click="resetTask"
              ></bbn-button>
              <bbn-button icon="nf nf-fa-stop"
                          title="<?=_('Stop')?>"
                          :notext="true"
                          class="bbn-left-sspace"
                          v-else
                          @click="stopTask"
              ></bbn-button>
              <bbn-button icon="nf nf-fa-trash"
                          title="<?=_('Delete all log files')?>"
                          @click="deleteAllLog"
                          :notext="true"
                          class="bbn-red bbn-left-sspace"
              ></bbn-button>
              <bbn-switch :value="true"
                          :novalue="false"
                          on-icon="nf nf-fa-refresh"
                          off-icon="nf nf-fa-refresh"
                          :no-icon="false"
                          @change="toggleAutoProcess"
                          :checked="!!autoProcess"
                          title="<?=_('Auto select the last process')?>"
                          class="bbn-left-sspace"
              ></bbn-switch>
            </bbn-toolbar>
            <div class="bbn-padded bbn-grid-fields">
              <label><?=_('File')?></label>
              <div v-text="currentTaskObj.file"></div>
              <label><?=_('Description')?></label>
              <div v-text="currentTaskObj.description"></div>
              <label><?=_('Priority')?></label>
              <div v-text="currentTaskObj.priority"></div>
              <label><?=_('Frequency')?></label>
              <div v-text="currentTaskObj.frequencyFormatted"></div>
              <label><?=_('Timeout')?></label>
              <div v-text="currentTaskObj.timeout"></div>
              <label><?=_('Prev')?></label>
              <div v-text="currentTaskObj.prev"></div>
              <label><?=_('Next')?></label>
              <div v-text="currentTaskObj.next"></div>
              <label><?=_('Process ID')?></label>
              <div :class="['bbn-b', {'bbn-red': currentTaskObj.failed}]"
                   v-text="source.pid"
                   :title="currentTaskObj.failed ? '<?=_('Failed')?>' : ''"
              ></div>
            </div>
          </div>
        </div>
        <div class="bbn-flex-fill bbn-flex-height">
          <div class="bbn-header bbn-spadded bbn-c bbn-b"><?=_('OUTPUT')?></div>
          <div class="bbn-flex-fill" v-if="currentLog">
            <bbn-code ref="code"
                      :value="currentOutput"
                      :readonly="true"
                      mode="clike"
                      class="bbn-overlay"
                      v-if="currentOutput"
            ></bbn-code>
            <div v-else
                 class="bbn-overlay  bbn-middle"
            ><?=_('No Output')?></div>
          </div>
          <div v-else
               class="bbn-overlay bbn-middle"
          >
            <span class="bbn-xl bbn-b"><i class="nf nf-fa-arrow_left bbn-right-space"></i><?=_('Select a process')?></span>
          </div>
        </div>
      </div>
    </bbn-pane>
  </bbn-splitter>
</div>