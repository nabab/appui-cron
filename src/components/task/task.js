(() => {
  return {
    data(){
      let today = moment().format('YYYY-MM-DD');
      return {
        today: today,
        currentDay: today,
        currentLog: false,
        currentLogIdx: null,
        currentContent: false,
        currentOutput: '',
        currentID: this.source.id,
        root: appui.plugins['appui-cron'] + '/',
        logTimeout: 0,
        refreshTimeout: 0,
        autoProcess: false,
        liveOutput: false,
        frequencies: [{
          value: 'i1',
          text: bbn._('Every minute')
        }, {
          value: 'i2',
          text: bbn._('Every 2 minutes')
        }, {
          value: 'i5',
          text: bbn._('Every 5 minutes')
        }, {
          value: 'i10',
          text: bbn._('Every 10 minutes')
        }, {
          value: 'i15',
          text: bbn._('Every 15 minutes')
        }, {
          value: 'i20',
          text: bbn._('Every 20 minutes')
        }, {
          value: 'i30',
          text: bbn._('Every 30 minutes')
        }, {
          value: 'i45',
          text: bbn._('Every 45 minutes')
        }, {
          value: 'h1',
          text: bbn._('Every hour')
        }, {
          value: 'h2',
          text: bbn._('Every 2 hours')
        }, {
          value: 'h4',
          text: bbn._('Every 4 hours')
        }, {
          value: 'h8',
          text: bbn._('Every 8 hours')
        }, {
          value: 'h12',
          text: bbn._('Every 12 hours')
        }, {
          value: 'd1',
          text: bbn._('Every day')
        }, {
          value: 'd2',
          text: bbn._('Every 2 days')
        }, {
          value: 'd3',
          text: bbn._('Every 3 days')
        }, {
          value: 'w1',
          text: bbn._('Every week')
        }, {
          value: 'w2',
          text: bbn._('Every 2 weeks')
        }, {
          value: 'w3',
          text: bbn._('Every 3 weeks')
        }, {
          value: 'm1',
          text: bbn._('Every month')
        }, {
          value: 'm2',
          text: bbn._('Every 2 month')
        }, {
          value: 'm3',
          text: bbn._('Every 3 month')
        }, {
          value: 'm6',
          text: bbn._('Every 6 month')
        }, {
          value: 'y1',
          text: bbn._('Every year')
        }],
        listFilters: [{
          value: 'content',
          text: bbn._('With content')
        }, {
          value: 'error',
          text: bbn._('With error')
        }],
        currentListFilter: null
      }
    },
    computed: {
      currentObj(){
        if ( bbn.fn.isNumber(this.currentLogIdx) ){
          let list = this.getRef('list'),
              item = list ? list.filteredData[this.currentLogIdx] : false;
          if ( item && item.data ){
            let data = bbn.fn.extend(true, {}, item.data);
            data.startFormatted = moment(data.start).format('DD/MM/YYYY HH:mm:ss');
            data.endFormatted = data.duration === undefined ? '' : moment(data.end).format('DD/MM/YYYY HH:mm:ss');
            return data;
          }
        }
        return {}
      },
      currentTaskObj(){
        if ( this.source ){
          let data = bbn.fn.extend(true, {}, this.source);
          if ( data.next && moment(data.next).isValid() ){
            data.nextFormatted = moment(data.next).format('DD/MM/YYYY HH:mm:ss');
          }
          if ( data.prev && moment(data.prev).isValid() ){
            data.prevFormatted = moment(data.prev).format('DD/MM/YYYY HH:mm:ss');
          }
          data.frequencyFormatted = data.frequency ? bbn.fn.getField(this.frequencies, 'text', {value: data.frequency}) : '';
          data.failed = data.pid && moment(data.prev).add(data.timeout, 's').isBefore();
          return data;
        }
        return {}
      },
      prevButtonDisabled(){
        let list = this.getRef('list');
        return bbn.fn.isNull(this.currentLogIdx) ||
          !list ||
          !list.filteredData.length ||
          (this.currentLogIdx === list.filteredData.length - 1)
      }
      ,
      nextButtonDisabled(){
        let list = this.getRef('list');
        return bbn.fn.isNull(this.currentLogIdx) || !list || !list.filteredData.length;
      }
    },
    methods: {
      _changeLog(idx){
        if ( !bbn.fn.isNull(this.currentLogIdx) ){
          let list = this.getRef('list');
          this.stopLive();
          if ( list.filteredData[idx] ){
            list.select(idx);
          }
          else if ( idx < 0 ){
            list.$once('dataloaded', () => {
              list.unselect();
              list.select(0);
            });
            list.updateData();
          }
        }
      },
      showLog(){
        if ( this.currentLog ){
          clearTimeout(this.logTimeout);
					this.liveOutput = true;
          this.post(this.root + 'data/log', {id: this.currentLog}, (d) => {
            if ( d.success && this.liveOutput ){
              this.currentOutput = d.log;
              this.currentFile = d.filename;
              this.currentFilePath = d.fpath[d.fpath.length-2] + '/';
              this.currentTreePath = [d.fpath];
              this.logTimeout = setTimeout(() => {
                this.showLog();
              }, appui.getRef('nav').activeRealContainer === this.closest('bbn-container') ? 2000 : 200000)
            }
          })
        }
      },
      startLive(){
        return;
        if ( this.currentID ){
          clearTimeout(this.logTimeout);
					this.liveOutput = true;
          this.post(this.root + 'data/log', {id: this.currentID}, d => {
            if ( d.success && this.liveOutput ){
              this.currentLog = d.start;
              this.currentOutput = d.log;
              this.currentContent = d.content;
              this.logTimeout = setTimeout(() => {
                this.startLive();
              }, appui.getRef('nav').activeRealContainer === this.closest('bbn-container') ? 2000 : 200000)
            }
          })
        }
        else {
          this.stopLive();
        }
      },
      stopLive(){
        clearTimeout(this.logTimeout);
        this.logTimeout = false;
				this.liveOutput = false;
      },
      deleteAllLog(){
        if ( this.currentLog ){
          this.confirm(bbn._('Are you sure you want to delete all log files of this task?'), () => {
            this.post(this.root + 'actions/log/delete_all', {id: this.currentID}, d => {
              if ( d.success ){
                appui.success(bbn._('Deleted'));
                this.currentDay = '';
                this.reset();
                this.getRef('calendar').reload();
              }
            });
          });
        }
      },
      toggleLive(){
        return;
        if ( this.currentID ){
          this.logTimeout ? this.stopLive() : this.startLive();
        }
        else if ( this.logTimeout ){
          this.stopLive();
        }
      },
      selectLog(row, idx){
        clearTimeout(this.logTimeout);
        this.liveOutput = false;
        this.currentLog = row.start;
        this.currentLogIdx = idx;
        this.currentContent = row.content;
      },
      listMounted(){
        let list = this.getRef('list');
        list.$once('dataloaded', () => {
          this.$nextTick(() => {
            list.select(0);
            if ( list.filteredData.length ){
              list.select(0);
            }
          })
        });
        list.updateData();
      },
      reset(){
        let list = this.getRef('list');
        if ( list.selected.length ){
          list.unselect();
        }
        this.currentListFilter = null;
        this.currentLog = false;
        this.currentLogIdx = null;
        this.currentContent = false;
        this.currentOutput = false;
      },
      nextLog(){
        if ( !bbn.fn.isNull(this.currentLogIdx) ){
          this._changeLog(this.currentLogIdx - 1)
        }
      },
      prevLog(){
        if ( !bbn.fn.isNull(this.currentLogIdx) ){
          this._changeLog(this.currentLogIdx + 1)
        }
      },
      refresh(){
        this.post(this.root + 'data/task/info', {id: this.currentID}, d => {
          if ( d.success && d.task ){
            bbn.fn.iterate(d.task, (v, i) => {
              this.$set(this.source, i, v);
            });
          }
        })
      },
      edit(){
        this.getPopup().open({
          title: bbn._('Edit'),
          width: 700,
          component: 'appui-cron-form-task',
          source: {
            row: this.source
          }
        });
      },
      onListDataReceived(d){
        if ( d.task ){
          clearTimeout(this.refreshTimeout);
          if (
            (this.currentDay === moment().format('YYYY-MM-DD')) &&
            d.task.next &&
            moment(d.task.next).isValid() &&
            moment(d.task.next).isAfter()
          ){
            this.refreshTimeout = setTimeout(() => {
              this.getRef('list').updateData();
              this.refresh();
            }, moment(d.task.next).diff() + 10000)
          }
        }
      },
      onListDataloaded(){
        this.$nextTick(() => {
          let list = this.getRef('list');
          if ( this.autoProcess ){
            list.select();
            list.select(0);
          }
          else {
            let idx = bbn.fn.search(list.filteredData, {'data.start': this.currentLog});
            this.currentLogIdx = bbn.fn.isNumber(idx) ? idx : 0;
          }
        })
      },
      toggleAutoProcess(){
        this.autoProcess = !this.autoProcess;
      },
      resetTask(){
        if ( this.currentID ){
          this.confirm(bbn._('Are you sure you want to reset this task? If the task is running you might crash the app'), () => {
            this.post(this.root + 'actions/task/reset', {id: this.currentID}, d => {
              if ( d.success ){
                this.refresh();
                appui.success(bbn._('Reset successful.'));
              }
            });
          });
        }
      },
      runTask(){

      },
      stopTask(){

      }
    },
    beforeDestroy(){
      clearTimeout(this.logTimeout);
      clearTimeout(this.refreshTimeout);
    },
    watch: {
      currentID(){
        this.currentDay = this.today;
      },
      currentContent(newVal){
        if ( newVal ){
          this.post(this.root + 'data/log', {file: newVal, id: this.currentID}, d => {
            this.currentOutput = d.success ? d.log : false;
          })
        }
        else {
          this.currentOutput = '';
        }
      },
      currentOutput(newVal){
        this.$nextTick(() => {
          if ( newVal ){
            let cm = this.getRef('code').widget;
            if ( cm ){
              cm.focus();
              // Set the cursor at the end of existing content
              cm.setCursor(cm.lineCount(), 0);
            }
          }
        })
      },
      currentDay(){
        this.reset();
        this.$nextTick(() => {
          this.getRef('list').updateData();
        })
      },
      currentListFilter(newVal){
        let list = this.getRef('list');
        list.currentFilters.conditions.splice(0);
        if ( newVal ){
          list.currentFilters.conditions.push({
            field: newVal,
            operator: '!=',
            value: false
          }, {
            field: newVal,
            operator: '!=',
            value: undefined
          })
        }
      }
    },
    components: {
      listItem: {
        template: `
          <div :class="['bbn-c', 'bbn-xspadded', 'bbn-bordered-bottom', {'bbn-green': !!source.content, 'bbn-red': !!source.error}]"
               v-text="time"
          ></div>
        `,
        props: {
          source: {
            type: Object
          }
        },
        computed: {
          time(){
            return moment(this.source.start).format('HH:mm:ss');
          }
        }
      }
    }
  }
})();