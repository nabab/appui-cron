// Javascript Document
(() => {
  return {
    //mixins: [bbn.vue.basicComponent],
    data(){
      return {
        interval: 0,
        currentFile: '',
        currentLog: false,
        currentCode: '',
        logTimeout: 0,
				autoLog: false
      }
    },
    computed: {
      realPollTime(){
        if ( this.source.polltime ){
          return (new Date(this.source.polltime*1000));
        }
        return false;
      },
      tasksList(){
        if ( this.source.tasks ){
          let tasks = this.source.tasks.slice();
          tasks.unshift({
            id: 'cron',
            description: 'CRON tasks system',
            file: 'CRON',
            cls: 'bbn-b'
          }, {
            id: 'poll',
            description: 'POLLER process',
            file: 'POLLER',
            cls: 'bbn-b'
          });
          return tasks;
        }
        return [];
      }
    },
    methods: {
      updateFileSystem(file, newVal){
        bbn.fn.post(this.source.root + 'actions/filesystem', {file: file, value: newVal}, (d) => {
          //bbn.fn.log(this.getTab());
          if ( !d.success ){
            this.$set(this.source, file, !newVal);
            appui.error(bbn._('Impossible to ' + (newVal ? 'create' : 'delete') + ' the file...'));
          }
          else{
            appui.success(bbn._('File ' + file + ' ' + (newVal ? 'created' : 'deleted') + ' successfully'));
          }
        })
      },
      updateTasks(){
        clearTimeout(this.logTimeout);
        this.currentFile = '';
        this.currentLog = false;
        this.currentCode = '';
				this.autoLog = false;
        bbn.fn.post(this.source.root + 'data/tasks', d => {
          if ( d.success && (d.tasks !== undefined) ){
            this.source.tasks = d.tasks;
          }
        });
      },
      fdate(d){
        return bbn.fn.fdate(d);
      },
      showLog(){
        if ( this.currentLog ){
          clearTimeout(this.logTimeout);
					this.autoLog = true;
          bbn.fn.post(this.source.root + 'data/log', {id: this.currentLog}, (d) => {
            if ( d.success && this.autoLog ){
              this.currentCode = d.log;
              this.currentFile = d.filename;
              this.logTimeout = setTimeout(() => {
                this.showLog();
              }, this.getTab().selected ? 2000 : 200000)
            }
          })
        }
      },
      stopLog(){
        clearTimeout(this.logTimeout);
        this.logTimeout = false;
				this.autoLog = false;
      },
      toggleAutoLog(){
        if ( this.currentLog ){
          if ( this.logTimeout ){
            this.stopLog();
          }
          else {
            this.showLog();
          }
        }
      },
      refresh(){
        this.interval = setTimeout(() => {
          bbn.fn.post(this.source.root + 'data/files', (d) => {
            //bbn.fn.log(d);
            for ( let n in d ){
              if ( d[n] !== this.source[n] ){
                this.$set(this.source, n, d[n]);
              }
            }
            this.refresh();
          })
        }, 5000)
      },
      select(idx){
        bbn.fn.log(this.source[idx]);
      },
      changeFile(act){
        if ( this.currentLog ){
          this.stopLog();
          bbn.fn.post(this.source.root + 'data/log', {
            id: this.currentLog,
            filename: this.currentFile,
            action: act
          }, d => {
            if ( d.log !== undefined ){
              this.currentCode = d.log;
              this.currentFile = d.filename;
            }
          });
        }
      },
      deleteLog(){
        if ( this.currentLog && this.currentFile ){
          let id = this.currentLog,
              file = this.currentFile;
          this.confirm(bbn._('Are you sure you want to delete this log file?'), () => {
            bbn.fn.post(this.source.root + 'actions/log/delete', {
              id: id,
              filename: file
            }, d => {
              if ( d.success ){
                appui.success(bbn._('Deleted'));
              }
            });
          });
        }
      },
      deleteAllLog(){
        if ( this.currentLog ){
          this.confirm(bbn._('Are you sure you want to delete all log files of this task?'), () => {
            bbn.fn.post(this.source.root + 'actions/log/delete_all', {id: this.currentLog}, d => {
              if ( d.success ){
                appui.success(bbn._('Deleted'));
              }
            });
          });
        }
      }
    },
    mounted(){
      this.refresh();
    },
    beforeDestroy(){
      clearTimeout(this.interval);
      clearTimeout(this.logTimeout);
    },
    watch: {
      currentLog(newVal){
        if ( newVal ){
          this.showLog();
        }
        else{
          this.currentCode = '';
        }
      },
      currentCode(){
        this.$nextTick(() => {
          let cm = this.getRef('code').widget;
          cm.focus();
          // Set the cursor at the end of existing content
          cm.setCursor(cm.lineCount(), 0);
        })
      },
      'source.active': function(newVal, oldVal){
        this.updateFileSystem('active', newVal)
      },
      'source.poll': function(newVal, oldVal){
        this.updateFileSystem('poll', newVal)
      },
      'source.cron': function(newVal, oldVal){
        this.updateFileSystem('cron', newVal)
      },
      generalActivity(newVal, oldVal){
        bbn.fn.log("CHANGING", newVal, oldVal);
      }
    },
    components: {
      tasksItem: {
        props: ['source'],
        template: `
<div :class="['bbn-w-100', {'bbn-state-selected': tab.currentLog === source.id, 'node': true}]">
  <div class="bbn-block">
    <span class="bbn-medium"
          :title="source.description"
          v-text="source.file"
    ></span>
  </div>
  <div class="bbn-block bbn-medium" style="float: right">
    <i v-if="source.next !== undefined"
       class="nf nf-fa-clock_o bbn-p"
       :title="info"
    ></i>
    <i class="nf nf-fa-file_text_o bbn-p"
       :title="_('See log')"
       @click="showLog"
    ></i>
  </div>
</div>`,
        data(){
          return {
            tab: bbn.vue.closest(this, 'bbn-container').getComponent()
          }
        },
        computed: {
          info(){
            return bbn._('Next') + ': ' + this.tab.fdate(this.source.next) + '\n' + bbn._('Last') + ': ' + this.tab.fdate(this.source.prev);
          }
        },
				methods: {
					showLog(){
						this.tab.currentLog = this.source.id;
						this.tab.autoLog = true;
					}
				}
      }
    }
  };
})();
