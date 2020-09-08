(() => {
  return {
    data(){
      return {
        currentFile: '',
        currentFilePath: '',
        currentLog: false,
        currentTree: false,
        currentTreePath: [],
        currentCode: '',
        currentID: false,
        root: appui.plugins['appui-cron'] + '/',
        logTimeout: 0,
        autoLog: false,
        treeVisible: false,
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
        }]
      }
    },
    computed: {
      currentFrequency(){
        return this.source && this.source.cfg && this.source.cfg.frequency ? (bbn.fn.getField(this.frequencies, 'text', {value: this.source.cfg.frequency}) || '') : ''
      }
    },
    methods: {
      changeFile(act){
        if ( this.currentLog ){
          this.stopLog();
          this.post(this.root + 'data/log', {
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
      showLog(){
        if ( this.currentLog ){
          clearTimeout(this.logTimeout);
					this.autoLog = true;
          this.post(this.root + 'data/log', {id: this.currentLog}, (d) => {
            if ( d.success && this.autoLog ){
              this.currentCode = d.log;
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
      stopLog(){
        clearTimeout(this.logTimeout);
        this.logTimeout = false;
				this.autoLog = false;
      },
      deleteLog(){
        if ( this.currentLog && this.currentFile ){
          let id = this.currentLog,
              file = this.currentFile;
          this.confirm(bbn._('Are you sure you want to delete this log file?'), () => {
            this.post(this.root + 'actions/log/delete', {
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
            this.post(this.root + 'actions/log/delete_all', {id: this.currentLog}, d => {
              if ( d.success ){
                appui.success(bbn._('Deleted'));
              }
            });
          });
        }
      },
      toggleAutoLog(){
        if ( this.currentLog ){
          this.logTimeout ? this.stopLog() : this.showLog();
        }
      },
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
      showFromPath(path){
        clearTimeout(this.logTimeout);
        this.post(this.root + 'data/log', {file: path, id: this.currentID}, (d) => {
          if ( d.success ){
            this.currentLog = this.currentID;
            this.currentCode = d.log;
            this.currentFile = d.filename;
            this.currentFilePath = d.fpath[d.fpath.length-2] + '/';
            this.currentTreePath = [d.fpath];
          }
        })
      }
    },
    mounted(){
      this.currentID = this.source.id;
      this.currentLog = this.source.id;
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
          if ( cm ){
            cm.focus();
            // Set the cursor at the end of existing content
            cm.setCursor(cm.lineCount(), 0);
          }
        })
      },
    }
  }
})();