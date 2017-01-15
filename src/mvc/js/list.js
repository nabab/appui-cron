// Javascript Document

var table = $("#cron_grid"),
    cols = [{
      field: "id",
      hidden: true
    }, {
      title: "Controller",
      field: "file",
      width: 150,
      template: function(e){
        return '<span class="' + ( e.active ? 'adherent' : 'radie' ) + '">' + e.file + '</span>';
      }
    }, {
      title: "Pri",
      field: "priority",
      width: 30
    }, {
      title: "Prev",
      field: "prev",
      width: 90,
      template: function(e){
        return bbn.fn.fdate(e.prev, 'Never');
      }
    }, {
      title: "Next",
      field: "next",
      width: 90,
      template: function(e){
        var color,
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
      }
    }, {
      title: "Dur.",
      field: "duration",
      width: 60,
      template: function(e){
        var d = parseFloat(e.duration);
        if ( d > 0 ) {
          return d < 10 ? d.toMoney() : Math.round(d);
        }
        return 0;
      }
    }, {
      title: "Num",
      field: "num",
      width: 70
    }, {
      title: "Description",
      field: "description"
    }
  ];
if ( data.is_dev ){
  cols.push({
    title: "Actions",
    sortable: false,
    field: "id",
    width: 120,
    template: function(e){
      var st = '<a class="k-button k-grid-edit" href="javascript:;"><i class="fa fa-edit" title="' + data.lng.edit + '"></i></a>';
      if ( e.active ){
        st += '<a class="k-button k-grid-delete" href="javascript:;"><i class="fa fa-times" title="' + data.lng.deactivate + '"></i></a>';
      }
      else{
        st += '<a class="k-button k-button-icontext k-grid-delete" href="javascript:;" title="' + data.lng.reactivate + '"><i class="fa fa-check"></i></a>';
      }
      if ( data.can_run ){
        st += '<button class="k-button appui-button-cron-run" href="javascript:;" title="' + data.lng.run + '"><i class="fa fa-play"></i></button>';
      }
      return st;
    }
  });
}
table.kendoGrid({
  toolbar: [
    {name: "create", text: "Nouvelle tâche automatisée CRON"}
  ],
  editable: {
    mode: "popup",
    template: apst.get_template("form_cron"),
    confirmation: false
  },
  dataBound: function(){
    var grid = this;
    $(".appui-button-cron-run", table).click(function(){
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