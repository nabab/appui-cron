// Javascript Document
(function(){
  let cp;
  return {
    props: {
      source: {
        type: Object
      }
    },
    data(){
      return {
        frequencies: [
          {value: 'i1', text: _('Every minute')},
          {value: 'i2', text: _('Every 2 minutes')},
          {value: 'i5', text: _('Every 5 minutes')},
          {value: 'i10', text: _('Every 10 minutes')},
          {value: 'i15', text: _('Every 15 minutes')},
          {value: 'i20', text: _('Every 20 minutes')},
          {value: 'i30', text: _('Every 30 minutes')},
          {value: 'i45', text: _('Every 45 minutes')},
          {value: 'h1', text: _('Every hour')},
          {value: 'h2', text: _('Every 2 hours')},
          {value: 'h4', text: _('Every 4 hours')},
          {value: 'h8', text: _('Every 8 hours')},
          {value: 'h12', text: _('Every 12 hours')},
          {value: 'd1', text: _('Every day')},
          {value: 'd2', text: _('Every 2 days')},
          {value: 'd3', text: _('Every 3 days')},
          {value: 'w1', text: _('Every week')},
          {value: 'w2', text: _('Every 2 weeks')},
          {value: 'w3', text: _('Every 3 weeks')},
          {value: 'm1', text: _('Every month')},
          {value: 'm2', text: _('Every 2 month')},
          {value: 'm3', text: _('Every 3 month')},
          {value: 'm6', text: _('Every 6 month')},
          {value: 'y1', text: _('Every year')}
        ]
      };
    },
    methods: {
      addRunning(a){
        a.isRunning = false;
        a.startRun = false;
        return a;
      },
      insert(){
        this.$refs.table.addTmp();
      },
      test(e){
        bbn.fn.log("test");
        return e.file + ' Yoohoo';
      },
      renderFile(e){
        return '<span class="' + ( e.active ? 'adherent' : 'radie' ) + '">' + e.file + '</span>';
      },
      renderNext(e){
        let color,
            mess = e.state;

        switch ( e.state ){
          case "progress":
            color = "blue";
            break;

          case "hold":
            color = "green";
            mess = bbn.fn.fdate(e.next);
            break;

          case "error":
            color = "red";
            break;

          case "progress_error":
            color = "red";
            mess = "progress";
            break;

          default:
            color = "DarkGray";
            mess = "unknown";
            break;
        }
        return '<span style="color: ' + color + '">' + mess + '</span>';
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
          icon: 'fa fa-edit',
          notext: true,
          command: 'edit'
        }];
        if ( e.active ){
          buttons.push({
            text: this._('Deactivate'),
            icon: 'fa fa-times',
            notext: true,
            command: this.deactivate
          });
        }
        else{
          buttons.push({
            text: this._('Activate'),
            icon: 'fa fa-check',
            notext: true,
            command: this.activate
          });
        }
        if ( this.source.can_run ){
          buttons.push({
            text: this._('Run task'),
            icon: 'fa fa-play',
            notext: true,
            command: this.run
          });
        }
        return buttons;
      },

      activate(e){

      },

      deactivate(e){

      },

      edit(e){

      },

      run(e){
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
            bbn.fn.alert(bbn._('An error occured...'))
          }
        })
      },
    },
    created(){
      cp = this;
    },
    components: {
      'appui-cron-switch': {
        props: ['source', 'value'],
        template: '<bbn-switch v-model="source.active" @change="activation"></bbn-switch>',
        methods: {
          activation(){
            let url = cp.source.root + 'actions/' + (this.source.active ? 'activate' : 'deactivate');
            bbn.fn.post(url, {id: this.source.id}, (d) => {
              if ( !d.success ){
                this.$set(this.source, 'active', !this.source.active);
                this.$forceUpdate();
              }
            })
          }
        }
      },
      'appui-cron-history': {
        name: 'appui-cron-history',
        props: ['source'],
        data(){
          return {
            parentTable: bbn.vue.closest(this, 'bbn-table')
          }
        },
        template: `
        <div class="bbn-w-100" style="height: 300px">
          <bbn-table :source="parentTable.source + '/' + source.id"
                     :pageable="true"
                     :serverPaging="true"
                     :limit="10"
          >
            <bbn-column field="start"
                        title="` + bbn._('Start') + `"
                        :width="150"
                        type="date"
            ></bbn-column>
            <bbn-column field="duration"
                        :width="150"
                        title="` + bbn._('Duration') + `"
                        :render="renderDuration"
            ></bbn-column>
            <bbn-column field="res"
                        title="` + bbn._('Result') + `"
                        :render="(d) => {return '<pre>' + d.res + '</pre>';}"
            ></bbn-column>
          </bbn-table>
        </div>`,
        methods: {
          renderDuration(e){
            let d = parseFloat(e.duration);
            if ( d > 0 ) {
              return d < 100 ? Math.round(d*1000)/1000 : Math.round(d);
            }
            return 0;
          },

        }
      }
    }
  };
  /*
  return function(ele, data){
    table.kendoGrid({
      toolbar: [
        {name: "create", text: "Nouvelle tâche automatisée CRON"}
      ],
      editable: {
        mode: "popup",
        template: appui.app.get_template("form_cron"),
        confirmation: false,
        window: {
          activate: function(e){
            bbn.fn.analyzeContent(e.sender.element);
          }
        }
      },
      dataBound: function(){
        var grid = this;
        $(".bbn-button-cron-run", table).click(function(){
          var it = grid.dataItem($(this).closest("tr"));
          bbn.fn.post(data.root + 'run', {id: it.id}, function(d){
            if ( d && d.file ){
              bbn.fn.popup(
                d.output ? d.output : data.lng.no_output,
                d.file + ' ' + data.lng.executed_in + ' ' + d.time + ' ' + data.lng.seconds,
                500
              )
            }
            else{
              bbn.fn.alert(data.lng.an_error_occured)
            }
          })
        });
      },
      edit: function(d){
        $("#dscawerejio98yI05").data("kendoDateTimePicker").setOptions({
          min: new Date()
        });
        $("#dscawerejio98yI00").next().on("click", function(){
          var $tree,
            treeDS = new kendo.data.HierarchicalDataSource({
              transport: {
                read: {
                  dataType: "json",
                  type: "POST",
                  url: "cron/tree",
                  data: {
                    appui: "json"
                  }
                }
              },
              schema: {
                data: "data",
                model: {
                  id: "path",
                  hasChildren: "is_parent",
                  fields:{
                    type: {type:"string"},
                    name: {type:"string"},
                    path: {type:"string"},
                    is_parent: {type:"bool"},
                    icon: {type:"string"}
                  }
                }
              }
            });
          bbn.fn.popup('<div class="tree"></tree>', 'Choose CLI', 250, 500, function(ele){
            $tree = $("div.tree", ele);
            $tree.kendoTreeView({
              dataTextField: "name",
              dataSpriteCssClassField: "icon",
              dataSource: treeDS,
              select: function(e){
                var r = this.dataItem(e.node);
                $("#dscawerejio98yI00").val(r.name).change();
                //d.model.set("file", r.name);
                bbn.fn.log(r.name, d.model, d.model.get("file"), d);
                bbn.fn.closePopup();
              }
            });
          });
        });
      },
      sortable: true,
      pageable: {
        refresh: true
      },
      columns: cols,
      dataSource: {
        pageSize: 50,
        sort:{
          field: "files",
          dir: "asc"
        },
        transport: {
          create: function(options) {
            bbn.fn.post(data.root + "list", bbn.fn.gridParse(options.data), function(d){
              options.success(d);
              table.data("kendoGrid").dataSource.read();
            });
          },
          read: function(options) {
            bbn.fn.post(data.root + "list", {json:1}, function(d){
              options.success(d);
            });
          },
          update: function(options) {
            bbn.fn.post(data.root + "list", bbn.fn.gridParse(options.data), function(d){
              options.success(d);
              table.data("kendoGrid").dataSource.read();
            });
          },
          destroy: function(options) {
            var action = options.data.active ? 'delete' : 'restore';
            bbn.fn.confirm(
              "Êtes-vous sûr vouloir " +
                (action === 'delete' ? "désactiver" : "réactiver") +
                " cette tâche automatisée?",
              "Confirmation d'action",
              function(){
                bbn.fn.post(data.root + "list", $.extend({}, bbn.fn.gridParse(options.data), {action: action}), function(d){
                  options.success(d);
                  table.data("kendoGrid").dataSource.read();
                });
              });
            return true;
          }
        },
        schema: {
          data: "data",
          total: "total",
          model: {
            id: "id",
            fields: [{
              field: "id",
              type: "number"
            }, {
              field: "project",
              type: "number",
              defaultValue: 1
            }, {
              field: "file",
              type: "string"
            }, {
              field: "description",
              type: "string"
            }, {
              field: "priority",
              type: "number",
              defaultValue: 5
            }, {
              field: "prev",
              type: "date"
            }, {
              field: "next",
              type: "date"
            }, {
              field: "frequency",
              type: "string"
            }, {
              field: "timeout",
              type: "number",
              defaultValue: 14400
            }, {
              type: "number",
              field: "active",
            }]
          }
        },
      },
      detailInit: function(e){
        var stable = $("<div/>");
        stable.appendTo(e.detailCell).kendoGrid({
          dataSource: {
            transport: {
              read: function(options) {
                bbn.fn.post(data.root + "list", $.extend({}, options.data, {id_cron: e.data.id, action: "journal"}), function(d){
                  options.success(d);
                });
              },
            },
            schema: {
              data: "data",
              total: "total",
              model: {
                fields: [{
                  field: "start",
                  type: "date",
                }, {
                  field: "finish",
                  type: "date",
                }, {
                  field: "res",
                  type: "string",
                }]
              }
            },
            pageSize:10,
          },
          sortable: true,
          scrollable: false,
          pageable: {
            refresh: true
          },
          columns: [{
            title: "Start",
            field: "start",
            width: 100,
            template: function(e){
              return bbn.fn.fdate(e.start, 'unknown');
            }
          }, {
            title: "End",
            field: "end",
            width: 100,
            template: function(e){
              //return bbn.fn.fdate(e.finish, 'unknown');
              var color,
                mess = e.state;

              switch ( e.state ){
                case "progress":
                  color = "blue";
                  break;

                case "error":
                  color = "red";
                  mess = bbn.fn.fdate(e.finish);
                  break;

                default:
                  color = "green";
                  mess = bbn.fn.fdate(e.finish);
                  break;
              }
              return '<span style="color: ' + color + '">' + mess + '</span>';
            }
          }, {
            title: "Résultat",
            field: "res",
            template: function(e){
              return bbn.fn.nl2br(e.res);
            },
            encoded: false
          }]
        });
      }
    });
  }
  */
})();