(() => {
  return {
    props: {
      source: {
        type: Object
      }
    },
    data(){
      return {
        root: appui.plugins['appui-cron'] + '/',
        currentDate: dayjs().format('YYYY-MM-DD HH:mm:ss'),
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
    methods: {
      browseCli(){
        this.getPopup().open({
          title: bbn._('Browse CLI'),
          width: 350,
          height: 600,
          component: this.$options.components.tree,
          source: this.source.row,
          scrollable: false
        });
      },
      onSuccess(d){
        if ( d.success ){
          let taskComp = this.closest('bbn-container').find('appui-cron-task'),
              table = this.closest('bbn-container').getComponent().getRef('table');
          if ( taskComp ){
            taskComp.refresh();
          }
          else if ( table ){
            table.updateData();
          }
          appui.success();
        }
        else {
          appui.error();
        }
      }
    },
    components: {
      tree: {
        template: `
<div class="bbn-spadding bbn-overlay">
  <bbn-tree :source="root + 'data/tree'"
            @select="nodeSel"
  ></bbn-tree>
</div>
        `,
        props: ['source'],
        data(){
          return {
            root: appui.plugins['appui-cron'] + '/'
          }
        },
        methods: {
          nodeSel(n){
            if ( !n.data.folder ){
              this.source.file = n.data.text;
              this.getPopup().close();
            }
          }
        }
      }
    }
  }
})();