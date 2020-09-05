// Javascript Document
(() => {
  return {
    //mixins: [bbn.vue.basicComponent],
    data(){
      return {
        interval: 0,
        currentFile: '',
        currentFilePath: '',
        currentLog: false,
        currentTree: false,
        currentTreePath: [],
        currentCode: '',
        currentID: false,
        treeVisible: false,
        logTimeout: 0,
				autoLog: false,
        isSelected: false,
        ready: false,
        taskInterval: 0
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
          let tasks = [];
          tasks.unshift({
            id: 'cron',
            current: !!this.source.cronid,
            description: bbn._('CRON tasks system'),
            file: bbn._('CRON'),
            cls: 'bbn-b' + (this.source.cronid ? ' bbn-red' : '')
          }, {
            id: 'poll',
            current: !!this.source.pollid,
            description: bbn._('POLLER process'),
            file: bbn._('POLLER'),
            cls: 'bbn-b' + (this.source.pollid ? ' bbn-red' : '')
          });
          bbn.fn.each(this.source.current, (a) => {
            let idx = bbn.fn.search(tasks, {id: a.id});
            if (idx > -1) {
              tasks.splice(idx, 1);
            }
            tasks.unshift(bbn.fn.extend({cls: 'bbn-red', current: true}, a));
          });
          return tasks;
        }
        return [];
      }
    },
    methods: {
      selectTree(node){
        this.showFromPath(node.getFullPath('/', 'name'));
      },
      treeMapper(a){
        return bbn.fn.extend({
          text: a.file ? a.name.substr(0, a.name.lastIndexOf('.')).substr(11).replace(/\-/g, ':') : a.name,
          id: a.name,
          type: a.dir ? 'dir' : 'file'
        }, a);
      },
      realSelect(id){
        this.currentID = id;
        this.currentLog = id;
      },
      select(cron){
        if (cron.id) {
          this.realSelect(cron.id);
        }
      },
      select1(cron){
        this.getRef('list2').unselect();
        this.getRef('list3').unselect();
        return this.select(cron);
      },
      select2(cron){
        this.getRef('list1').unselect();
        this.getRef('list3').unselect();
        return this.select(cron);
      },
      select3(cron){
        this.getRef('list1').unselect();
        this.getRef('list2').unselect();
        return this.select(cron);
      },
      updateFileSystem(file, newVal){
        this.post(this.source.root + 'actions/control', {file: file, value: newVal}, (d) => {
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
      fdate(d){
        return bbn.fn.fdate(d);
      },
      showFromPath(path){
        clearTimeout(this.logTimeout);
        this.post(this.source.root + 'data/log', {file: path, id: this.currentID}, (d) => {
          if ( d.success ){
            this.currentLog = this.currentID;
            this.currentCode = d.log;
            this.currentFile = d.filename;
            this.currentFilePath = d.fpath[d.fpath.length-2] + '/';
            this.currentTreePath = [d.fpath];
          }
        })
      },
      showLog(){
        if ( this.currentLog ){
          clearTimeout(this.logTimeout);
					this.autoLog = true;
          this.post(this.source.root + 'data/log', {id: this.currentLog}, (d) => {
            if ( d.success && this.autoLog ){
              this.currentCode = d.log;
              this.currentFile = d.filename;
              this.currentFilePath = d.fpath[d.fpath.length-2] + '/';
              this.currentTreePath = [d.fpath];
              this.logTimeout = setTimeout(() => {
                this.showLog();
              }, appui.getRef('nav').activeRealContainer === this.getTab() ? 2000 : 200000)
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
        clearTimeout(this.interval);
        this.post(this.source.root + 'data/files', (d) => {
          for ( let n in d ){
            if ( d[n] !== this.source[n] ){
              this.$set(this.source, n, d[n]);
            }
          }
          clearTimeout(this.interval);
          this.$forceUpdate();
          this.interval = setTimeout(() => {
            this.refresh();
          }, 5000);
        });
      },
      refreshTasks(){
        clearTimeout(this.taskInterval);
        this.post(this.source.root + 'data/tasks', (d) => {
          clearTimeout(this.taskInterval);
          if (this.ready) {
            this.source.tasks = d.tasks || [];
            this.$forceUpdate();
            this.taskInterval = setTimeout(() => {
              this.refreshTasks();
            }, 30000);
          }
        });
      },
      changeFile(act){
        if ( this.currentLog ){
          this.stopLog();
          this.post(this.source.root + 'data/log', {
            id: this.currentLog,
            filename: this.currentFile,
            fpath: this.currentFilePath,
            action: act
          }, d => {
            if ( d.log !== undefined ){
              this.currentCode = d.log;
              this.currentFile = d.filename;
              this.currentFilePath = d.fpath[d.fpath.length-2] + '/';
              this.currentTreePath.splice(0);
              this.currentTreePath.push(d.fpath);
            }
          });
        }
      },
      deleteLog(){
        if ( this.currentLog && this.currentFile ){
          let id = this.currentLog,
              file = this.currentFile;
          this.confirm(bbn._('Are you sure you want to delete this log file?'), () => {
            this.post(this.source.root + 'actions/log/delete', {
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
            this.post(this.source.root + 'actions/log/delete_all', {id: this.currentLog}, d => {
              if ( d.success ){
                appui.success(bbn._('Deleted'));
              }
            });
          });
        }
      },
      toggleActive(){
        this.confirm(bbn._('Are you sure you want to') + ' ' + 
                     (this.source.active ? bbn._('turn off') : bbn._('turn on')) + ' ' +
                     bbn._('all background activity?'), () => {
          this.source.active = !this.source.active;
        })
      },
      togglePoll(){
        this.confirm(bbn._('Are you sure you want to') + ' ' + 
                     (this.source.poll ? bbn._('turn off') : bbn._('turn on')) + ' ' +
                     bbn._('the polling system?'), () => {
          this.source.poll = !this.source.poll;
        })
      },
      toggleCron(){
        this.confirm(bbn._('Are you sure you want to') + ' ' + 
                     (this.source.cron ? bbn._('turn off') : bbn._('turn on')) + ' ' +
                     bbn._('the task system?'), () => {
          this.source.cron = !this.source.cron;
        })
      },
      mouseOver(e) {
        if (!e.target.childNodes[0].classList.contains('bbn-hover')) {
          e.target.childNodes[0].classList.add('bbn-hover');
        }
      },
      mouseOut(e) {
        if (e.target.childNodes[0].classList.contains('bbn-hover')) {
          e.target.childNodes[0].classList.remove('bbn-hover');
        }
      },
    },
    mounted(){
      this.ready = true;
      this.interval = setTimeout(this.refresh, 5000);
      this.taskInterval = setTimeout(this.refreshTasks, 30000);
    },
    beforeDestroy(){
      this.ready = false;
      clearTimeout(this.interval);
      clearTimeout(this.taskInterval);
      clearTimeout(this.logTimeout);
    },
    watch: {
      currentID(){
        this.treeVisible = false;
        setTimeout(() => {
          this.treeVisible = true;
        }, 100)
      },
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
<div :class="['bbn-w-100', 'bbn-hspadded', 'bbn-vxspadded', 'node']">
  <div class="bbn-flex-width">
		<div class="bbn-flex-fill"
				 style="white-space: nowrap">
      <bbn-countdown :title="info" :target="source.next" precision="second" scale="minute">
        <i v-if="source.next !== undefined"
           class="nf nf-fa-clock_o bbn-p"
        ></i>
			</bbn-countdown> &nbsp; 
      <span class="bbn-medium bbn-iblock"
            :title="source.description"
            style="text-overflow: ellipsis"
            v-html="source.file"
      ></span>
    </div>
  </div>
</div>`,
        data(){
          return {
            tab: bbn.vue.closest(this, 'bbn-container').getComponent()
          }
        },
        computed: {
          info(){
            let d = bbn.fn.date(this.source.next);
            let m = new moment(d);
            let st = bbn._('Next execution') + ': ';
            if (m.isValid()) {
              st += m.calendar() + ' (' + m.fromNow() + ')'
            }
            else {
              st += bbn._('Unknown');
            }
            st += "\n" + bbn._('Previous execution') + ': ';
            d = bbn.fn.date(this.source.prev);
            m = new moment(d);
            if (m.isValid()) {
              st += m.calendar() + ' (' + m.fromNow() + ')'
            }
            else {
              st += bbn._('Unknown');
            }
            return st;
          }
        }
      },
      failedItem: {
        props: ['source'],
        template: `
<div :class="['bbn-w-100', 'bbn-hspadded', 'bbn-vxspadded']">
  <div class="bbn-flex-width bbn-vmiddle">
    <i class="nf nf-fa-warning bbn-right-sspace bbn-red"></i>
    <span class="bbn-medium bbn-flex-fill"
          :title="source.description"
          style="text-overflow: ellipsis"
          v-html="source.file"
    ></span>
  </div>
</div>`
      },
      activeTasksItem: {
        props: ['source'],
        template: `
<div class="bbn-w-100 bbn-hspadded bbn-vxspadded node">
  <div class="bbn-flex-width">
		<div class="bbn-flex-fill"
				 style="white-space: nowrap">
      <span class="bbn-medium bbn-iblock"
            :title="source.description"
            style="text-overflow: ellipsis"
            v-html="source.file"
      ></span>
    </div>
  </div>
</div>`,
        data(){
          return {
            tab: bbn.vue.closest(this, 'bbn-container').getComponent()
          }
        },
        computed: {
          info(){
            let d = bbn.fn.date(this.source.next);
            let m = new moment(d);
            let st = bbn._('Next execution') + ': ';
            if (m.isValid()) {
              st += m.calendar() + ' (' + m.fromNow() + ')'
            }
            else {
              st += bbn._('Unknown');
            }
            st += "\n" + bbn._('Previous execution') + ': ';
            d = bbn.fn.date(this.source.prev);
            m = new moment(d);
            if (m.isValid()) {
              st += m.calendar() + ' (' + m.fromNow() + ')'
            }
            else {
              st += bbn._('Unknown');
            }
            return st;
          }
				}
      }
    }
  };
})();