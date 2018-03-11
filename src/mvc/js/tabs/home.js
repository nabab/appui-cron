// Javascript Document
(() => {
  return {
    mixins: [bbn.vue.basicComponent],
    data(){
      return {
        interval: 0,
        currentLog: false,
        currentCode: '',
        logTimeout: 0
      }
    },
    computed: {
      realPollTime(){
        if ( this.source.polltime ){
          return (new Date(this.source.polltime*1000));
        }
        return false;
      }
    },
    methods: {
      updateFileSystem(file, newVal){
        bbn.fn.post(this.source.root + 'actions/filesystem', {file: file, value: newVal}, (d) => {
          bbn.fn.log(this.getTab());
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
      showLog(){
        if ( this.currentLog ){
          clearTimeout(this.logTimeout)
          bbn.fn.post(this.source.root + 'data/log', {id: this.currentLog}, (d) => {
            if ( d.log ){
              this.currentCode = d.log;
              this.logTimeout = setTimeout(() => {
                this.showLog();
              }, this.getTab().selected ? 2000 : 200000)
            }
          })
        }
      },
      refresh(){
        bbn.fn.log(this.getTab().selected);
        this.interval = setTimeout(() => {
          bbn.fn.post(this.source.root + 'data/files', (d) => {
            bbn.fn.log(d);
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
    }
  };
})()