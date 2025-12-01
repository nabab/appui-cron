(() => {
  return {
    data(){
      return {
        dataInterval: false
      }
    },
    methods: {
      renderDescription(row){
        return `<span title="${row.file}">${row.description}</span>`;
      }
    },
    mounted(){
      if ( this.dataInterval ){
        clearTimeout(this.dataInterval);
      }
      this.dataInterval = setInterval(() => {
        this.closest('bbn-widget').reload();
      },30000);
    },
    beforeDestroy() {
      if ( this.dataInterval ){
        clearTimeout(this.dataInterval);
      }
    },
    components: {
      next: {
        template: `<span v-text="showedTime"></span>`,
        props: {
          source: {
            type: Object
          }
        },
        data(){
          return {
            nextInterval: false,
            currentTime: bbn.dt(this.source.next),
            showedTime: 0
          }
        },
        methods: {
          refresh(){
            /*
            let dur = bbn.dt().duration(this.currentTime.diff(bbn.dt()));
              if ( bbn.dt().isSame(this.currentTime, 'month') ){
                let h = dur.hours(),
                    m = dur.minutes(),
                    s = dur.seconds();
                if ( (h <= 0) && (m <= 0) && (s <= 0) ){
                  this.showedTime = bbn._('Running');
                }
                else {
                  this.showedTime = `${h.toString().length < 2 ? '0' : ''}${h}:${m.toString().length < 2 ? '0' : ''}${m}:${s.toString().length < 2 ? '0' : ''}${s}`;
                }
              }
              else {
                this.showedTime = this.currentTime.calendar();
              }
              */
          }
        },
        mounted(){
          /*
          if ( this.nextInterval ){
            clearTimeout(this.nextInterval);
          }
          if ( this.source.pid ){
            this.currentTime = bbn._('Running');
          }
          else {
            this.refresh();
            this.nextInterval = setInterval(() => {
              this.refresh();
            }, 1000);
          }
          */
        },
        beforeDestroy(){
          if ( this.nextInterval ){
            clearTimeout(this.nextInterval);
          }
        }
      }
    }
  }
})();