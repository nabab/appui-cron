// Javascript Document
(() => {
  return {
    props: {
      source: {
        type: Object
      }
    },
    data(){
      return {
        currentDate: moment().format('YYYY-MM-DD HH:mm:ss'),
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
      };
    },
    methods: {
      addRunning(a){
        a.isRunning = false;
        a.startRun = false;
        return a;
      },
      insert(){
        this.$refs.table.insert();
      },
      test(e){
        bbn.fn.log("test");
        return e.file + ' Yoohoo';
      },
      renderFile(e){
        return '<span class="bbn-' + ( e.active ? 'green' : 'red' ) + '">' + e.file + '</span>';
      },
      renderAvgDuration(e){
        let d = parseFloat(e.duration);
        if ( d > 0 ) {
          return d < 10 ? Math.round(d*100)/100 : Math.round(d);
        }
        return 0;
      },
      renderDuration(e){
        let d = parseFloat(e.duration);
        if ( d > 0 ) {
          return d < 100 ? Math.round(d*1000)/1000 : Math.round(d);
        }
        return 0;
      },
      renderButtons(e){
        let buttons = [{
          text: this._('Edit'),
          icon: 'nf nf-fa-edit',
          notext: true,
          command: 'edit'
        }];
        if ( this.source.can_delete ){
          buttons.push({
            text: this._('Delete task'),
            icon: 'nf nf-fa-trash',
            notext: true,
            command: this.remove
          });
        }
        if ( this.source.can_run ){
          buttons.push({
            text: this._('Run task'),
            icon: 'nf nf-fa-play',
            notext: true,
            command: this.run
          });
        }
        return buttons;
      },
      remove(e){
        if ( e.id ){
          this.confirm(bbn._('Are you sure you want to delete this task?'), () => {
            bbn.fn.post(this.source.root + 'actions/task/delete', {id: e.id}, d => {
              if ( d.success ){
                this.$refs.table.updateData();
                appui.success('Deleted.');
              }
            });
          });
        }
      },
      run(e){
        this.confirm(bbn._('Are you sure you want to run this task?'), () => {
          e.isRunning = true;
          bbn.fn.post(this.source.root + 'run', {id: e.id}, (d) => {
            e.isRunning = false;
            if ( d && d.file ){
              this.getTab().popup(
                d.output ? d.output : bbn._('No output'),
                d.file + ' ' + bbn._('executed in') + ' ' + d.time + ' ' + bbn._('seconds'),
                500
              );
            }
            else{
              this.alert(bbn._('An error occured...'));
            }
          });
        });
      }
    },
    components: {
      'appui-cron-switch': {
        props: ['source', 'value'],
        template: '<bbn-switch v-model="source.active" @change="activation" :no-icon="false"></bbn-switch>',
        data(){
          return {
            cp: bbn.vue.closest(this, 'bbn-container').getComponent()
          }
        },
        methods: {
          activation(){
            let url = this.cp.source.root + 'actions/task/' + (this.source.active ? 'activate' : 'deactivate');
            bbn.fn.post(url, {id: this.source.id}, (d) => {
              if ( !d.success ){
                this.$set(this.source, 'active', !this.source.active);
                this.$forceUpdate();
              }
            });
          }
        }
      },
      'appui-cron-error': {
        name: 'appui-cron-error',
        props: ['source'],
        template: `
        <div class="bbn-w-100" style="height: 300px">
          <bbn-table :source="cp.source.root + '/data/error'"
                     :data="{id: source.id}"
                     :pageable="true"
                     :serverPaging="true"
                     :limit="10"
                     ref="table"
          >
            <bbns-column field="moment"
                        title="` + bbn._('Date') + `"
                        :width="150"
                        type="date"
                        cls="bbn-c"
            ></bbns-column>
            <bbns-column field="content"
                        title="` + bbn._('Content') + `"
            ></bbns-column>
            <bbns-column :buttons="[{
                          text: 'Delete',
                          command: deleteLog,
                          notext: true,
                          icon: 'nf nf-fa-trash'
                        }]"
                        :tcomponent="$options.components['appui-cron-error-delete']"
                        :width="60"
                        cls="bbn-c"
            ></bbns-column>
          </bbn-table>
        </div>`,
        data(){
          return {
            cp: bbn.vue.closest(this, 'bbn-container').getComponent()
          }
        },
        methods: {
          deleteLog(row){
            if ( this.cp.source.can_delete_error && this.source.id && row.filename ){
              this.confirm(bbn._('Are you sure you want to delete this error log?'), () => {
                bbn.fn.post(this.cp.source.root + 'actions/log/delete_error', {
                  id: this.source.id,
                  filename: row.filename
                }, d => {
                  if ( d.success ){
                    this.$refs.table.updateData();
                    appui.success(bbn._('Deleted'));
                  }
                  else {
                    appui.error(bbn._('Error'));
                  }
                });
              });
            }
          }
        },
        components: {
          'appui-cron-error-delete': {
            name: 'appui-cron-error-delete',
            template: `
<bbn-button icon="nf nf-fa-trash_alt" 
            @click="deleteAll" 
            title="` + bbn._('Delete all logs') + `"
            :disabled="!cp.source.can_delete_all_error"
            style="color: red"
></bbn-button>`,
            data(){
              return {
                cp: bbn.vue.closest(this, 'bbn-container').getComponent(),
                cpError: this.$parent.$parent
              }
            },
            methods: {
              deleteAll(){
                if ( this.cp.source.can_delete_all_error && this.cpError.source.id ){
                  this.confirm(bbn._('Are you sure you want to delete all error logs of this task?'), () => {
                    bbn.fn.post(this.cp.source.root + 'actions/log/delete_all_error', {id: this.cpError.source.id}, d => {
                      if ( d.success ){
                        this.cpError.$refs.table.updateData();
                        appui.success(bbn._('All logs deleted'));
                      }
                      else {
                        appui.error(bbn._('Error'));
                      }
                    });
                  });
                }
              }
            }
          }
        }
      },
      'appui-cron-controller': {
        template: `
<div class="bbn-flex-width">
  <bbn-input v-model="form.source.file"
             required 
             maxlength="100"
             class="bbn-flex-fill"
  ></bbn-input>
  <bbn-button @click="browseCli">{{_('Browse CLI')}}</bbn-button>
</div>
        `,
        data(){
          return {
            form: bbn.vue.closest(this, 'bbn-form')
          }
        },
        methods: {
          browseCli(){
            this.getPopup().open({
              title: bbn._('Browse CLI'),
              width: 350,
              component: this.$options.components['appui-cron-tree'],
              source: this.form.source
            });
          }
        },
        components: {
          'appui-cron-tree': {
            template: `
<div class="bbn-spadded bbn-overlay">
  <bbn-tree class=""
            :source="cp.source.root + 'data/tree'"
            @select="nodeSel"
  ></bbn-tree>
</div>
            `,
            props: ['source'],
            data(){
              return {
                cp: bbn.vue.closest(this, 'bbn-container').getComponent()
              }
            },
            methods: {
              nodeSel(n){
                if ( !n.data.folder ){
                  this.source.file = n.text;
                  this.getPopup().close();
                }
              }
            }
          }
        }
      }
    }
  }
})();