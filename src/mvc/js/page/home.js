// Javascript Document
(() => {
  return {
    data(){
      return {
        showTask: false,
        currentTask: false,
        taskSource: {},
        ready: false
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
      selectByDD(id){
        this.getRef('list1').unselect();
        this.getRef('list2').unselect();
        this.getRef('list3').unselect();
        this.select(id);
      },
      select(id){
        if (id) {
          this.showTask = false;
          this.post(this.source.root + 'data/task/info', {id: id}, d => {
            if ( d.success ){
              this.taskSource = d.task;
              this.currentTask = id
              this.showTask = true;
            }
          });
        }
      },
      select1(cron){
        this.getRef('list2').unselect();
        this.getRef('list3').unselect();
        return this.select(cron.id);
      },
      select2(cron){
        this.getRef('list1').unselect();
        this.getRef('list3').unselect();
        return this.select(cron.id);
      },
      select3(cron){
        this.getRef('list1').unselect();
        this.getRef('list2').unselect();
        return this.select(cron.id);
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
      refreshTasks(){
        this.post(this.source.root + 'data/tasks', (d) => {
          if (this.ready) {
            this.source.tasks = d.tasks || [];
            this.$forceUpdate();
          }
        });
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
      receive(d){
        if ('tasks' in d) {
          this.source.tasks = d.tasks.tasks || [];
          this.$forceUpdate();
        }
        if ('files' in d) {
          bbn.fn.iterate(d.files, (v, p) => {
            if ( v !== this.source[p] ){
              this.$set(this.source, p, v);
            }
          })
          this.$forceUpdate();
        }
      }
    },
    created(){
      appui.register('appui-cron', this);
    },
    mounted(){
      this.ready = true;
      this.$set(appui.pollerObject, 'appui-cron', {
        tasksHash: false,
        filesHash: false
      });
      appui.poll();
    },
    beforeDestroy(){
      this.ready = false;
      this.$delete(appui.pollerObject, 'appui-cron');
      appui.poll();
      appui.unregister('appui-cron');
    },
    watch: {
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
    <bbn-countdown :title="info"
                   :target="source.next"
                   precision="second"
                   scale="minute"
    >
      <i v-if="source.next !== undefined"
         class="nf nf-fa-clock_o bbn-p"
      ></i>
    </bbn-countdown>
    <div class="bbn-flex-fill bbn-left-sspace">
      <div class="bbn-medium bbn-ellipsis"
            :title="source.description"
            v-html="source.file"
      ></div>
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